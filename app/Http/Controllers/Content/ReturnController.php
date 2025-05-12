<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BorrowTransaction;
use App\Models\Book;
use App\Models\School;

class ReturnController extends Controller
{
    public function showBorrowTransactions(Request $request)
    {
        if ($request->ajax()) {
            $query = BorrowTransaction::query()->with('book', 'user', 'transaction', 'transaction.referenceCode.bookRequest.school', 'returnTransactions', 'transaction.referenceCode');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('book_id')) {
                $query->where('book_id', $request->book_id);
            }
            if ($request->filled('school_id')) {
                $query->whereHas('transaction.referenceCode.bookRequests', function ($q) use ($request) {
                    $q->where('school_id', $request->school_id);
                });
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('book_id', 'like', '%' . $search . '%')
                  ->orWhere('user_id', 'like', '%' . $search . '%')
                  ->orWhere('transaction_id', 'like', '%' . $search . '%')
                  ->orWhereHas('transaction.referenceCode', function ($q) use ($search) {
                  $q->where('reference_code', 'like', '%' . $search . '%');
                  });
            });
            }

            $totalRecords = $query->count();

            $query->orderByRaw("FIELD(status, 'borrowed', 'partially_returned') DESC");

            $orderColumnIndex = $request->input('order')[1]['column'] ?? 1;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'borrow_id';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $borrowTransactions = $query->skip($start)->take($length)->get();

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $borrowTransactions
            ]);
        }

        $books = Book::where('status', 'available')->get();
        $schools = School::where('status', 'active')->get();

        return view('contents.return-books', compact('books', 'schools'));
    }
}
