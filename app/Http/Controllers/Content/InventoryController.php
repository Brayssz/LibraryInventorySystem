<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\School;

class InventoryController extends Controller
{
    public function showInventory(Request $request)
    {
        if ($request->ajax()) {
            $query = School::where('status', 'active')->with(['inventory.book', 'inventory']);

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->whereHas('inventory.book', function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('author', 'like', '%' . $search . '%')
                        ->orWhere('isbn', 'like', '%' . $search . '%');
                });
            }
            

            if ($request->filled('school_id')) {
                $schoolId = $request->input('school_id');
                $query->where('school_id', $schoolId);
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'name';
            if ($orderColumn === 'school') {
                $orderColumn = 'name';
            }
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $schools = $query->skip($start)->take($length)->get();

            $books = Book::where('status', 'available')->get();

            $data = $schools->flatMap(function ($school) use ($books, $request) {
                return $books->filter(function ($book) use ($request) {
                    return !$request->filled('book_id') || $book->book_id == $request->input('book_id');
                })->map(function ($book) use ($school) {
                    $inventory = $school->inventory->firstWhere('book_id', $book->book_id);
                    $received = $inventory ? $inventory->transactions()->where('transaction_type', 'received')->sum('quantity') : 0;
                    $lost = $inventory ? $inventory->transactions()->where('transaction_type', 'lost')->sum('quantity') : 0;
                    return [
                        'inventory_id' => $inventory->inventory_id ?? null,
                        'book_id' => $book->book_id,
                        'school_id' => $school->school_id,
                        'school' => $school->name,
                        'books' => [
                            [
                                'title' => $book->title,
                                'quantity' => $inventory->quantity ?? 0,
                                'received' => $received,
                                'lost' => $lost
                            ]
                        ]
                    ];
                });
            });

            

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $data
            ]);
        }

        $schools = School::where('status', 'active')->with(['inventory.book'])->get();
        $books = Book::all();

        $data = $schools->flatMap(function ($school) use ($books) {
            return $books->map(function ($book) use ($school) {
                $inventory = $school->inventory->firstWhere('book_id', $book->book_id);
                $received = $inventory ? $inventory->transactions()->where('transaction_type', 'received')->sum('quantity') : 0;
                $lost = $inventory ? $inventory->transactions()->where('transaction_type', 'lost')->sum('quantity') : 0;
                return [
                    'inventory_id' => $inventory->inventory_id ?? null,
                    'school' => $school->name,
                    'books' => [
                        [
                            'title' => $book->title,
                            'quantity' => $inventory->quantity ?? 0,
                            'received' => $received,
                            'lost' => $lost
                        ]
                    ]
                ];
            });
        });

        $l_books = Book::where('status', 'available')->get();
        $l_schools = School::where('status', 'active')->get();

        return view('contents.inventory', compact('data', 'l_books', 'l_schools'));
        // return response()->json($data);
    }

    public function showDivisionTotal(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search') && !empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('author', 'like', '%' . $search . '%')
                ->orWhere('isbn', 'like', '%' . $search . '%');
        }

        if ($request->filled('book_id')) {
            $bookId = $request->input('book_id');
            $query->where('book_id', $bookId);
        }

        $totalRecords = $query->count();

        $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
        $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'title';
        $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
        $query->orderBy($orderColumn, $orderDirection);

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $books = $query->skip($start)->take($length)->get();

        $data = $books->map(function ($book) {
            $inventories = Inventory::where('book_id', $book->book_id)->get();
            $totalAvailable = $inventories->sum('quantity');
            $totalReceived = $inventories->sum(function ($inventory) {
                return $inventory->transactions()->where('transaction_type', 'received')->sum('quantity');
            });
            $totalLost = $inventories->sum(function ($inventory) {
                return $inventory->transactions()->where('transaction_type', 'lost')->sum('quantity');
            });

            return [
                'book_id' => $book->book_id,
                'title' => $book->title,
                'total_quantity' => $totalReceived + $totalLost,
                'total_received' => $totalReceived,
                'total_lost' => $totalLost,
                'total_available' => $totalAvailable
            ];
        });

        return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ]);
    }

}
