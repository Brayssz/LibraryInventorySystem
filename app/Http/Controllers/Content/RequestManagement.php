<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookRequest;
use App\Models\Book;

class RequestManagement extends Controller
{
    public function showRequests(Request $request)
    {
        if ($request->ajax()) {
            $query = BookRequest::with(['school', 'book']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('school_id', 'like', '%' . $search . '%')
                      ->orWhere('book_id', 'like', '%' . $search . '%')
                      ->orWhere('quantity', 'like', '%' . $search . '%');
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
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

        $query = BookRequest::query();
        $requests = $query->get();

        $books = Book::where('status', 'available')->get();

        return view('content.request-management', compact('requests', 'books'));
    }
}
