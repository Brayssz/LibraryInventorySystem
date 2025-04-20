<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\School;

class BookRequestController extends Controller
{
    public function showRequests(Request $request)
    {
        if ($request->ajax()) {
            $query = BookRequest::with(['school', 'book', 'referenceCode']);
 
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if($request->filled('book_id')) {
                $query->where('book_id', $request->book_id);
            }

            if($request->filled('school_id')) {
                $query->where('school_id', $request->school_id);
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('school_id', 'like', '%' . $search . '%')
                        ->orWhere('book_id', 'like', '%' . $search . '%')
                        ->orWhere('quantity', 'like', '%' . $search . '%')
                        ->orWhereHas('referenceCode', function ($q) use ($search) {
                            $q->where('reference_code', 'like', '%' . $search . '%');
                        });
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[1]['column'] ?? 1;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'request_id';
            $query->orderByRaw("FIELD(status, 'pending') DESC");
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $requests = $query->skip($start)->take($length)->get();

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $requests
            ]);
        }

        $books = Book::where('status', 'available')->get();
        $schools = School::where('status', 'active')->get();

        return view('contents.request-management', compact('books', 'schools'));
    }

    public function getAvailableBooks(Request $request)
    {
        $query = Book::query()
            ->whereHas('inventory', function ($q) {
                $q->where('quantity', '!=', 0)
                    ->where('location_type', 'division');
            })
            ->with(['inventory' => function ($q) {
                $q->where('location_type', 'division');
            }]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search') && !empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%');
            });
        }

        $totalRecords = $query->count();

        $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
        $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'book_id';
        $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
        $query->orderBy($orderColumn, $orderDirection);

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $books = $query->skip($start)->take($length)->get();

        return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $books
        ]);
    }
}
