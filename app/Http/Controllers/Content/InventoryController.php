<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\School;
use App\Models\BorrowTransaction;

class InventoryController extends Controller
{
    public function showInventory(Request $request)
    {
        if ($request->ajax()) {
            $query = School::where('status', 'active')->with(['inventory.book', 'inventory']);
        
            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->whereHas('inventory.book', function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('author', 'like', "%$search%")
                        ->orWhere('isbn', 'like', "%$search%");
                });
            }
        
            if ($request->filled('school_id')) {
                $query->where('school_id', $request->input('school_id'));
            }
        
            $schools = $query->get();
            $books = Book::where('status', 'available')->get();
        
            $data = collect();
        
            foreach ($schools as $school) {
                foreach ($books as $book) {
                    if (!$request->filled('book_id') || $book->book_id == $request->input('book_id')) {
                        $inventory = $school->inventory->firstWhere(fn($inv) => $inv->book_id == $book->book_id && $inv->location_type == 'school');
                        
                        $received = $inventory ? $inventory->transactions()->where('transaction_type', 'receive')->sum('quantity') : 0;
                        $lost = $inventory ? BorrowTransaction::whereHas('transaction', fn($query) => $query->where('inventory_id', $inventory->inventory_id))->sum('quantity_lost') : 0;
                        
                        $data->push([
                            'inventory_id' => $inventory->inventory_id ?? null,
                            'book_id' => $book->book_id,
                            'book_photo_path' => $book->book_photo_path,
                            'school_id' => $school->school_id,
                            'school' => $school->name,
                            'books' => [[
                                'title' => $book->title,
                                'quantity' => $inventory->quantity ?? 0,
                                'received' => $received,
                                'lost' => $lost
                            ]]
                        ]);
                    }
                }
            }
        
            $totalRecords = $data->count();
            
            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'school';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            
            $data = $data->sortBy($orderColumn, SORT_REGULAR, $orderDirection === 'desc')->values();
        
            $start = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 10);
            $data = $data->slice($start, $length)->values();
        
            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $data
            ]); 
        }

     

        $l_books = Book::where('status', 'available')->get();
        $l_schools = School::where('status', 'active')->get();

        return view('contents.inventory', compact( 'l_books', 'l_schools'));
        
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
            $divisionInventories = Inventory::where('book_id', $book->book_id)->where('location_type', 'division')->sum('quantity');
            $inventories = Inventory::where('book_id', $book->book_id)->where('location_type', 'school')->get();
            $totalAvailable = $inventories->sum('quantity');
            $totalLost = $inventories->sum(function ($inventory) {
                return BorrowTransaction::whereHas('transaction', function ($query) use ($inventory) {
                    $query->where('inventory_id', $inventory->inventory_id);
                })->sum('quantity_lost');
            });

            return [
                'book_id' => $book->book_id,
                'book_photo_path' => $book->book_photo_path,
                'title' => $book->title,
                'total_quantity' => $divisionInventories,
                'total_received' => $divisionInventories + $totalLost + $totalAvailable,
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
