<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BookRequest;
use App\Models\BorrowTransaction;
use App\Models\ReturnTransaction;
use App\Models\Book;

class PDFController extends Controller
{
    public function showDeliveryTransactions(Request $request)
    {
        $query = Transaction::where('transaction_type', 'delivery')->with('referenceCode', 'approvedBy', 'inventory.book');

        $startDate = null;
        $endDate = null;

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
            $query->whereBetween('transaction_timestamp', [$startDate, $endDate]);
        }

        $transactions = $query->get();

        $report = [];

        foreach ($transactions as $transaction) {
            $report[] = [
                'reference_code' => $transaction->referenceCode->reference_code ?? null,
                'book_title' => $transaction->inventory->book->title ?? null,
                'quantity' => $transaction->quantity,
                'approved_by' => $transaction->approvedBy->name ?? 'N/A',
                'date' => Carbon::parse($transaction->transaction_timestamp)->format('F j, Y'),
                'time' => Carbon::parse($transaction->transaction_timestamp)->format('h:i:s A'),
            ];
        }

        $pdf = Pdf::loadView('pdf.delivery-transaction-report-pdf', compact('report', 'startDate', 'endDate'));

        return $pdf->stream('delivery_transactions_report.pdf');
    }

    public function showBookRequests(Request $request)
    {
        $query = BookRequest::with('referenceCode', 'school', 'approvedBy', 'book');
    
        $startDate = null;
        $endDate = null;
    
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $bookRequests = $query->get();
        $report = [];
    
        foreach ($bookRequests as $bookRequest) {
            $report[] = [
                'reference_code' => $bookRequest->referenceCode->reference_code ?? null,
                'book_title' => $bookRequest->book->title ?? null,
                'school_name' => $bookRequest->school->name ?? null,
                'quantity' => $bookRequest->quantity ?? 0,
                'status' => $bookRequest->status ?? 'N/A',
                'date' => Carbon::parse($bookRequest->created_at)->format('F j, Y'),
                'time' => Carbon::parse($bookRequest->created_at)->format('h:i:s A'),
            ];
        }
    
        $pdf = Pdf::loadView('pdf.book-requests-report-pdf', compact('report', 'startDate', 'endDate'));
        
        return $pdf->stream('book_requests_report.pdf');
    }

    public function showBooksDistribution(Request $request)
    {
        $query = BookRequest::with('referenceCode', 'school', 'approvedBy', 'book')->where('status', 'approved');

        $startDate = null;
        $endDate = null;

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $bookRequests = $query->get();
        $report = [];

        foreach ($bookRequests as $bookRequest) {
            $report[] = [
                'reference_code' => $bookRequest->referenceCode->reference_code ?? null,
                'book_title' => $bookRequest->book->title ?? null,
                'school_name' => $bookRequest->school->name ?? null,
                'quantity' => $bookRequest->quantity,
                'quantity_released' => $bookRequest->quantity_released ?? 0,
                'approved_by' => $bookRequest->approvedBy->name ?? 'N/A',
                'date' => Carbon::parse($bookRequest->updated_at)->format('F j, Y'),
                'time' => Carbon::parse($bookRequest->updated_at)->format('h:i:s A'),
            ];
        }

        $pdf = Pdf::loadView('pdf.books-distribution-report-pdf', compact('report', 'startDate', 'endDate'));

        return $pdf->stream('books_distribution_report.pdf');
    }

    public function showBorrowingTransaction(Request $request)
    {
        $query = BorrowTransaction::query()->whereIn('status', ['returned', 'borrowed', 'partially_returned'])
            ->with([
                'transaction.referenceCode',
                'transaction.inventory.book',
                'transaction.referenceCode.bookRequest.school'
            ]);

        $startDate = null;
        $endDate = null;

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
            $query->whereBetween('borrow_timestamp', [$startDate, $endDate]);
        }

        $transactions = $query->get();
        $report = [];

        foreach ($transactions as $borrow) {
            $report[] = [
                'reference_code' => $borrow->transaction->referenceCode->reference_code ?? null,
                'school_name' => $borrow->transaction->referenceCode->bookRequest->school->name ?? null,
                'book_name' => $borrow->transaction->referenceCode->bookRequest->book->title ?? null,
                'quantity_borrowed' => $borrow->transaction->quantity,
                'quantity_returned' => $borrow->returnTransactions->sum('quantity'),
                'quantity_lost' => $borrow->quantity_lost,
                'date' => Carbon::parse($borrow->borrow_timestamp)->format('F j, Y'),
                'time' => Carbon::parse($borrow->borrow_timestamp)->format('h:i:s A'),
            ];
        }

        $pdf = Pdf::loadView('pdf.borrowing-transaction-report-pdf', compact('report', 'startDate', 'endDate'));

        return $pdf->stream('borrowing_transaction_report.pdf');
    }

    public function showReturnedBooks(Request $request)
    {
        $query = ReturnTransaction::query()
            ->with([
                'borrowTransaction.transaction.referenceCode',
                'borrowTransaction.transaction.inventory.book',
                'borrowTransaction.transaction.referenceCode.bookRequest.school',
                'recordedBy'
            ]);

        $startDate = null;
        $endDate = null;

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
            $query->whereBetween('return_date', [$startDate, $endDate]);
        }

        $returnTransactions = $query->get();
        $report = [];

        foreach ($returnTransactions as $returnTransaction) {
            $borrowTransaction = $returnTransaction->borrowTransaction;
            $report[] = [
                'reference_code' => $borrowTransaction->transaction->referenceCode->reference_code ?? null,
                'school_name' => $borrowTransaction->transaction->referenceCode->bookRequest->school->name ?? null,
                'book_name' => $borrowTransaction->transaction->inventory->book->title ?? null,
                'quantity_returned' => $returnTransaction->quantity,
                'recorded_by' => $returnTransaction->recordedBy->name ?? 'Unknown',
                'date' => Carbon::parse($returnTransaction->return_date)->format('F j, Y'),
                'time' => Carbon::parse($returnTransaction->return_date)->format('h:i:s A'),
            ];
        }

        $pdf = Pdf::loadView('pdf.returned-books-report-pdf', compact('report', 'startDate', 'endDate'));

        return $pdf->stream('returned_books_report.pdf');
    }

    public function showBookInventory(Request $request)
    {
        $query = Book::with('inventory');

        $books = $query->get();
        $report = [];

        foreach ($books as $book) {
            $divisionInventory = $book->inventory->firstWhere('location_type', 'division');
            $schoolInventories = $book->inventory->where('location_type', 'school');

            $quantityDelivered = $divisionInventory ? Transaction::where('inventory_id', $divisionInventory->inventory_id)
                ->where('transaction_type', 'delivery')
                ->sum('quantity') : 0;

            $schoolInventoryIds = $schoolInventories->pluck('inventory_id');
            $transactions = Transaction::whereIn('inventory_id', $schoolInventoryIds)
                ->where('transaction_type', 'receive')
                ->with('borrowTransaction.returnTransactions')
                ->get();

            $quantityReceivedInSchools = $transactions->sum('quantity');

            $quantityOnDivision = $quantityDelivered - $quantityReceivedInSchools;

            $quantityBorrowed = $quantityReceivedInSchools;
            $quantityReturned = $transactions->sum(function ($transaction) {
                return $transaction->borrowTransaction->returnTransactions->sum('quantity');
            });

            $quantityLost = $quantityBorrowed - $quantityReturned;

            $report[] = [
                'book_title' => $book->title,
                'quantity_delivered' => $quantityDelivered,
                'quantity_on_division' => $quantityOnDivision + $quantityReturned,
                'quantity_borrowed' => $quantityBorrowed,
                'quantity_returned' => $quantityReturned,
                'quantity_lost' => $quantityLost,
            ];
        }

        $pdf = Pdf::loadView('pdf.book-inventory-report-pdf', compact('report'));

        return $pdf->stream('book_inventory_report.pdf');
    }
}
