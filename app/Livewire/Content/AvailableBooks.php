<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\Book;

class AvailableBooks extends Component
{

    public function getAvailableBooks($page = 1, $searchQuery = '')
    {
        $search = trim($searchQuery);

        $availableBooks = Book::where('status', 'available')
            ->whereHas('inventory', function ($query) {
                $query->where('quantity', '>', 0)
                      ->where('location_type', 'division');
            })
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
            })
            ->with(['inventory' => function ($query) {
                $query->selectRaw('book_id, SUM(quantity) as total_quantity')
                      ->where('location_type', 'division')
                      ->groupBy('book_id');
            }]);

        $availableBooks = $availableBooks->paginate(12, ['*'], 'page', $page);

        $availableBooks->getCollection()->transform(function ($book) {
            $book->total_quantity = $book->inventory->sum('total_quantity');
            return $book;
        });

        return response()->json($availableBooks);
    }
    public function render()
    {
        return view('livewire.content.available-books');
    }
}
