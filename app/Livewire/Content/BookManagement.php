<?php

namespace App\Livewire\Content;

use App\Models\Book;
use Livewire\Component;
use Illuminate\Validation\Rule;

class BookManagement extends Component
{
    public $submit_func;

    public $book;

    public $total_books;

    public $book_id, $title, $author, $published_date, $status;

    public function getBook($bookId)
    {
        $this->book = Book::where('book_id', $bookId)->first();

        if ($this->book) {
            $this->book_id = $this->book->book_id;
            $this->title = $this->book->title;
            $this->author = $this->book->author;
            $this->published_date = date('Y', strtotime($this->book->published_date));
            $this->status = $this->book->status;
        } else {
            session()->flash('error', 'Book not found.');
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date_format:Y|before_or_equal:' . date('Y'),
            'status' => 'nullable|string|max:255',
        ];
    }

    public function render()
    {
        return view('livewire.content.book-management');
    }

    public function resetFields()
    {
        $this->reset([
            'title', 'author', 'published_date', 'status'
        ]);
    }

    public function submit_book()
    {
        $this->validate();

        if ($this->submit_func == "add-book") {
            $book = Book::create([
                'title' => $this->title,
                'author' => $this->author,
                'published_date' => $this->published_date . '-01-01',
            ]);

            session()->flash('message', 'Book successfully created.');

        } else if ($this->submit_func == "edit-book") {
            $this->book->title = $this->title;
            $this->book->author = $this->author;
            $this->book->published_date = $this->published_date . '-01-01';
            $this->book->status = $this->status;

            $this->book->save();

            session()->flash('message', 'Book successfully updated.');
        }

        return redirect()->route('books');
    }
}
