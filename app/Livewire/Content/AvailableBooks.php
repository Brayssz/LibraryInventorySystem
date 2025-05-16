<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\Book;

class AvailableBooks extends Component
{

    public function getAvailableBooks($page = 1, $searchQuery = '', $order = '')
    {
        $search = trim($searchQuery);

        $availableBooks = Book::where('status', 'available')
            ->whereHas('inventory', function ($query) {
                $query->where('quantity', '>', 0)
                    ->where('location_type', 'division');
            })
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('published_date', 'like', "%{$search}%");
            })
            ->with(['inventory' => function ($query) {
                $query->selectRaw('book_id, SUM(quantity) as total_quantity')
                    ->where('location_type', 'division')
                    ->groupBy('book_id');
            }]);

        // Handle sorting based on $order
        switch ($order) {
            case 'title_desc':
                $availableBooks->orderBy('title', 'desc');
                break;
            case 'author_asc':
                $availableBooks->orderBy('author', 'asc');
                break;
            case 'author_desc':
                $availableBooks->orderBy('author', 'desc');
                break;
            case 'year_asc':
                $availableBooks->orderBy('published_date', 'asc');
                break;
            case 'year_desc':
                $availableBooks->orderBy('published_date', 'desc');
                break;
            case 'title_asc':
            default:
                $availableBooks->orderBy('title', 'asc');
                break;
        }

        $availableBooks = $availableBooks->paginate(20, ['*'], 'page', $page);

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
