<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Inventory;
use App\Models\School;
use App\Models\BorrowTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\ReferenceCode;

class AppController extends Controller
{
    public function showRequestForm(Request $request)
    {
        if (session()->has('school_id_expires_at') && now()->lessThan(session('school_id_expires_at'))) {
            $school_id = session('school_id');

            return view('contents.request-form', compact('school_id'));
        } else {
            session()->forget('school_id');
            session()->forget('school_id_expires_at');

            return redirect('/login')->withErrors('Session expired, please log in again.');
        }
    }

    public function showDashboard(Request $request)
    {
        $total_users = User::count();
        $total_books = Book::count();
        $total_requests = BookRequest::where('status', 'pending')->count();
        $total_schools = School::count();

        $pending_requests = BookRequest::where('status', 'pending')->with(['school', 'book'])->get();

        $overdueTransactions = BorrowTransaction::where('status', 'due')
            ->whereHas('transaction.referenceCode.bookRequest', function ($query) {
            $query->whereNotNull('expected_return_date')
                ->where('expected_return_date', '<', Carbon::today());
            })
            ->with(['transaction.referenceCode.bookRequest.school', 'transaction.referenceCode.bookRequest.book'])
            ->get();

        $overdueDetails = $overdueTransactions->map(function ($transaction) {
            $bookRequest = $transaction->transaction->referenceCode->bookRequest;
            $dueDate = Carbon::parse($bookRequest->expected_return_date);
            $now = Carbon::today();

            $diffInHours = $dueDate->diffInHours($now);
            $diffInDays = $dueDate->diffInDays($now);

            $timeDue = $diffInHours < 24 
            ? "{$diffInHours} hours" 
            : "{$diffInDays} days and " . ($diffInHours % 24) . " hours";

            return [
            'school' => $bookRequest->school->name,
            'book' => $bookRequest->book->title,
            'time_due' => $timeDue,
            'requested_qty' => $bookRequest->quantity,
            ];
        });

        $top_borrowed_books = BorrowTransaction::join('books', 'borrow_transactions.book_id', '=', 'books.book_id')
            ->join('transactions', 'borrow_transactions.transaction_id', '=', 'transactions.transaction_id')
            ->select('books.title', 'books.published_date', 'books.book_photo_path', DB::raw('SUM(transactions.quantity) as total_borrowed'))
            ->groupBy('books.title', 'books.published_date', 'books.book_photo_path')
            ->orderBy('total_borrowed', 'desc')
            ->limit(10)
            ->get();

        $total_pending_return = BorrowTransaction::where('status', 'borrowed')->count();
        $total_partially_returned = BorrowTransaction::where('status', 'partially_returned')->count();
        $total_due = BorrowTransaction::where('status', 'due')->count();

        return view('contents.dashboard', compact('total_pending_return', 'total_partially_returned', 'total_due','total_users', 'total_books', 'total_requests', 'total_schools', 'pending_requests', 'top_borrowed_books', 'overdueDetails'));
    }

    public function getMonthlyTransactionData()
    {
        $borrowed = BorrowTransaction::join('transactions', 'borrow_transactions.transaction_id', '=', 'transactions.transaction_id')
            ->select(
                DB::raw("MONTH(borrow_transactions.borrow_timestamp) as month"),
                DB::raw("SUM(transactions.quantity) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $returned = BorrowTransaction::join('return_transactions', 'borrow_transactions.borrow_id', '=', 'return_transactions.borrow_id')
            ->select(
                DB::raw("MONTH(borrow_transactions.borrow_timestamp) as month"),
                DB::raw("SUM(return_transactions.quantity) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $lost = BorrowTransaction::select(
            DB::raw("MONTH(borrow_timestamp) as month"),
            DB::raw("SUM(quantity_lost) as total")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = range(1, 12);
        $borrowedData = [];
        $returnedData = [];
        $lostData = [];

        foreach ($months as $month) {
            $borrowedData[] = $borrowed[$month] ?? 0;
            $returnedData[] = $returned[$month] ?? 0;
            $lostData[] = $lost[$month] ?? 0;
        }

        return response()->json([
            'borrowed' => $borrowedData,
            'returned' => $returnedData,
            'lost' => $lostData,
        ]);
    }

    public function landingPage()
    {
        $total_books = Book::where('status', 'available')->count();
        $total_schools = School::where('status', 'active')->count();
        $total_copies = Inventory::where('location_type', 'division')->sum('quantity');
        $top_books = $this->displayTopBooks();

        return view('welcome', compact('top_books', 'total_books', 'total_schools', 'total_copies'));
    }

    public function displayTopBooks()
    {
        $top_books =  BorrowTransaction::join('books', 'borrow_transactions.book_id', '=', 'books.book_id')
            ->join('transactions', 'borrow_transactions.transaction_id', '=', 'transactions.transaction_id')
            ->select(
                'books.title',
                'books.published_date',
                'books.book_photo_path',
                'books.author',
                'books.created_at',
                DB::raw('SUM(transactions.quantity) as total_borrowed')
            )
            ->groupBy('books.title', 'books.published_date', 'books.book_photo_path', 'books.author', 'books.created_at')
            ->orderBy('total_borrowed', 'desc')
            ->limit(5)
            ->get();

        return $top_books;
    }
}
