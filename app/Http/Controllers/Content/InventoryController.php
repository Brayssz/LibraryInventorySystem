<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\School;

class InventoryController extends Controller
{
    public function showInventory()
    {
        $schools = School::where('status', 'active')->with(['inventory.book'])->get();
        $books = Book::all();

        $data = $schools->flatMap(function ($school) use ($books) {
            return $books->map(function ($book) use ($school) {
                
                $inventory = $school->inventory->firstWhere('book_id', $book->book_id);
                $received = $inventory ? $inventory->transactions()->where('transaction_type', 'received')->sum('quantity') : 0;
                $lost = $inventory ? $inventory->transactions()->where('transaction_type', 'lost')->sum('quantity') : 0;
                return [
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

        return response()->json($data);
    }
}
