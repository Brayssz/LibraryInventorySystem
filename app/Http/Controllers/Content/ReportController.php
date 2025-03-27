<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Book;
use App\Models\Inventory;
use App\Models\School;
use App\Models\BorrowTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\BookRequest;

class ReportController extends Controller
{

    public function exportInventoryReport()
    {
        $templatePath = public_path('excel/inventory_template.xlsx');

        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Template file not found!'], 404);
        }

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $response = $this->showGenerateReport(new Request());
        $reportData = json_decode($response->getContent(), true);

        $columnIndex = 'G';
        foreach ($reportData[0]['schools'] as $school) {
            $sheet->setCellValue($columnIndex . '10', $school['school_name']);
            $sheet->setCellValue($columnIndex . '11', 'No. of cps Received');
            $columnIndex++;
            $sheet->setCellValue($columnIndex . '11', 'Available');
            $columnIndex++;
            $sheet->setCellValue($columnIndex . '11', 'Missing/Lost');
            $columnIndex++;
        }

        $row = 13;
        foreach ($reportData as $report) {
            $sheet->setCellValue('A' . $row, $report['title']);
            $sheet->setCellValue('B' . $row, $report['date_of_inventory']);
            $sheet->setCellValue('C' . $row, $report['division_total']['num_copies_delivered']);
            $sheet->setCellValue('D' . $row, $report['division_total']['actual_num_slrs']);
            $sheet->setCellValue('E' . $row, $report['division_total']['available']);
            $sheet->setCellValue('F' . $row, $report['division_total']['missing_lost']);

            $columnIndex = 'G';
            foreach ($report['schools'] as $school) {
                foreach ($school['inventory'] as $inventory) {
                    $sheet->setCellValue("{$columnIndex}{$row}", $inventory['num_cps_received']);
                    $columnIndex++;
                    $sheet->setCellValue("{$columnIndex}{$row}", $inventory['available']);
                    $columnIndex++;
                    $sheet->setCellValue("{$columnIndex}{$row}", $inventory['missing_lost']);
                    $columnIndex++;
                }
            }

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'inventory_report');
        $writer->save($tempFile);

        $fileContent = file_get_contents($tempFile);
        unlink($tempFile);

        $fileName = 'SLRs_Inventory_Report_' . now()->format('Ymd') . '.xlsx';
        return response()->json([
            'fileName' => $fileName,
            'fileContent' => base64_encode($fileContent)
        ]);
    }

    public function showGenerateReport(Request $request)
    {
        $query = Book::query();
        $books = $query->get();

        $data = $books->map(function ($book) {
            $divisionInventories = Inventory::where('book_id', $book->book_id)->where('location_type', 'division')->sum('quantity');
            $inventories = Inventory::where('book_id', $book->book_id)->where('location_type', 'school')->get();
            $totalAvailable = $inventories->sum('quantity');
            $totalLost = $inventories->sum(function ($inventory) {
                return BorrowTransaction::whereHas('transaction', function ($query) use ($inventory) {
                    $query->where('inventory_id', $inventory->inventory_id);
                })->sum('quantity_lost');
            });

            $schools = School::where('status', 'active')->get()->map(function ($school) use ($book) {
                $inventory = $school->inventory->firstWhere(function ($inv) use ($book) {
                    return $inv->book_id == $book->book_id && $inv->location_type == 'school';
                });
                $received = $inventory ? $inventory->transactions()->where('transaction_type', 'receive')->sum('quantity') : 0;
                $lost = $inventory ? BorrowTransaction::whereHas('transaction', function ($query) use ($inventory) {
                    $query->where('inventory_id', $inventory->inventory_id);
                })->sum('quantity_lost')
                    : 0;
                return [
                    'school_name' => $school->name,
                    'inventory' => [
                        [
                            'num_cps_received' => $received,
                            'available' => $inventory->quantity ?? 0,
                            'missing_lost' => $lost
                        ]
                    ]
                ];
            });

            return [
                'title' => $book->title,
                'date_of_inventory' => now()->format('Y-m-d'),
                'division_total' => [
                    'num_copies_delivered' => $divisionInventories,
                    'actual_num_slrs' => $totalAvailable + $totalLost,
                    'available' => $totalAvailable,
                    'missing_lost' => $totalLost
                ],
                'schools' => $schools
            ];
        });

        return response()->json($data);
    }

    public function showDeliveryTransactions(Request $request)
    {
        if ($request->ajax()) {
            $query = Transaction::query()->where('transaction_type', 'delivery')->with('referenceCode', 'approvedBy');

            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->date_range);
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('transaction_timestamp', [$startDate, $endDate]);
            }

            $transactions = $query->get();

            $report = [];
            $totalRecords = 0;

            foreach ($transactions as $transaction) {
                $report[] = [
                    'reference_code' => $transaction->referenceCode->reference_code,
                    'quantity' => $transaction->quantity,
                    'approved_by' => $transaction->approvedBy->name,
                    'date' => Carbon::parse($transaction->transaction_timestamp)->format('F j, Y'),
                    'time' => Carbon::parse($transaction->transaction_timestamp)->format('h:i:s A'),
                ];

                $totalRecords++;
            }

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $report
            ]);
        }

        return view('reports.delivery-transaction-report');
    }

    public function showBooksDistribution(Request $request)
    {
        if ($request->ajax()) {

            $query = BookRequest::query()->with('referenceCode', 'school', 'approvedBy')->where('status', 'approved');

            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->date_range);
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            }

            $bookRequests = $query->get();

            $report = [];
            $totalRecords = 0;

            foreach ($bookRequests as $bookRequest) {
                $report[] = [
                    'reference_code' => $bookRequest->referenceCode->reference_code,
                    'school_name' => $bookRequest->school->name,
                    'quantity_released' => $bookRequest->quantity_released,
                    'approved_by' => $bookRequest->approvedBy->name,
                    'date' => Carbon::parse($bookRequest->updated_at)->format('F j, Y'),
                    'time' => Carbon::parse($bookRequest->updated_at)->format('h:i:s A'),
                ];

                $totalRecords++;
            }

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $report
            ]);
        }

        return view('reports.books-distribution-report');
    }

    public function showBookRequests(Request $request)
    {
        if ($request->ajax()) {

            $query = BookRequest::query()->with('referenceCode', 'school', 'approvedBy');

            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->date_range);
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $bookRequests = $query->get();

            $report = [];
            $totalRecords = 0;

            foreach ($bookRequests as $bookRequest) {
                $report[] = [
                    'reference_code' => $bookRequest->referenceCode->reference_code,
                    'school_name' => $bookRequest->school->name,
                    'quantity' => $bookRequest->quantity,
                    'date' => Carbon::parse($bookRequest->created_at)->format('F j, Y'),
                    'time' => Carbon::parse($bookRequest->created_at)->format('h:i:s A'),
                    'status' => $bookRequest->status,
                ];

                $totalRecords++;
            }

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $report
            ]);
        }

        return view('reports.book-requests-report');
    }

    public function showBorrowingTransaction(Request $request)
    {
        if ($request->ajax()) {

            $query = BorrowTransaction::query()->where('status', 'Returned')
                ->with([
                    'transaction.referenceCode',
                    'transaction.inventory.book',
                    'transaction.referenceCode.bookRequests.school'
                ]);

            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->date_range);
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('borrow_timestamp', [$startDate, $endDate]);
            }

            $transactions = $query->get();

            $report = [];
            $totalRecords = 0;

            foreach ($transactions as $borrow) {
                $report[] = [
                    'reference_code' => $borrow->transaction->referenceCode->reference_code ?? null,
                    'school_name' => $borrow->transaction->referenceCode->bookRequests->first()->school->name ?? null,
                    'book_name' => $borrow->transaction->referenceCode->bookRequests->first()->book->title ?? null,
                    'quantity_borrowed' => $borrow->transaction->quantity,
                    'quantity_returned' => $borrow->returnTransactions->sum('quantity'),
                    'quantity_lost' => $borrow->quantity_lost,
                    'date' => Carbon::parse($borrow->borrow_timestamp)->format('F j, Y'),
                    'time' => Carbon::parse($borrow->borrow_timestamp)->format('h:i:s A'),
                ];

                $totalRecords++;
            }

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $report
            ]);
        }

        return view('reports.borrowing-transaction-report');
    }

    public function showReturnedBooks(Request $request)
    {
        if ($request->ajax()) {

            $query = BorrowTransaction::query()->where('status', 'Returned')
                ->with([
                    'transaction.referenceCode',
                    'transaction.inventory.book',
                    'transaction.referenceCode.bookRequests.school',
                    'returnTransactions'
                ]);

            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->date_range);
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            }

            $borrows = $query->get();

            $report = [];
            $totalRecords = 0;

            foreach ($borrows as $borrow) {
                $report[] = [
                    'reference_code' => $borrow->transaction->referenceCode->reference_code ?? null,
                    'school_name' => $borrow->transaction->referenceCode->bookRequests->first()->school->name ?? null,
                    'book_name' => $borrow->transaction->referenceCode->bookRequests->first()->book->title ?? null,
                    'quantity_returned' => $borrow->returnTransactions->sum('quantity'),
                    'recorded_by' => $borrow->user->name ?? 'Unknown',
                    'date' => Carbon::parse($borrow->updated_at)->format('F j, Y'),
                    'time' => Carbon::parse($borrow->updated_at)->format('h:i:s A'),
                ];

                $totalRecords++;
            }

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $report
            ]);
        }

        return view('reports.returned-books-report');
    }
}
