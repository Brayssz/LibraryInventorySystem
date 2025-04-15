<?php

namespace App\Livewire\Content;

use App\Models\Book;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class BookManagement extends Component
{
    use WithFileUploads;
    public $submit_func;
    public $book;

    public $total_books;

    public $book_id, $title, $author, $published_date, $status;

    public $photo;
    public $photoPreview;

    public function getBook($bookId)
    {
        $this->book = Book::where('book_id', $bookId)->first();

        if ($this->book) {
            $this->book_id = $this->book->book_id;
            $this->title = $this->book->title;
            $this->author = $this->book->author;
            $this->published_date = date('Y', strtotime($this->book->published_date));
            $this->status = $this->book->status;

            $this->photoPreview = $this->getBookPhotoUrl($this->book);

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

    
    public function getBookPhotoUrl(Book $book): string
    {
        $bookPhotoDisk = config('filesystems.default', 'public');

        return $book->book_photo_path
            ? Storage::disk($bookPhotoDisk)->url($book->book_photo_path)
            : "";
    }

    public function updateBookPhoto(UploadedFile $photo, Book $book, $storagePath = 'book-photos')
    {
        $bookPhotoDisk = 'public';

        $fileName = 'book_' . $book->book_id . '_' . strtolower(str_replace(' ', '_', $book->title)) . '.' . $photo->getClientOriginalExtension();

        tap($book->book_photo_path, function ($previous) use ($photo, $book, $fileName, $bookPhotoDisk, $storagePath) {
            if ($previous) {
                Storage::disk($bookPhotoDisk)->delete($previous);
            }
            $book->forceFill([
                'book_photo_path' => $photo->storeAs(
                    $storagePath,
                    $fileName,
                    ['disk' => $bookPhotoDisk]
                ),
            ])->save();

            
        });
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

            if ($this->photo) {
                $this->updateBookPhoto($this->photo, $book);
            }

            session()->flash('message', 'Book successfully created.');

        } else if ($this->submit_func == "edit-book") {
            $this->book->title = $this->title;
            $this->book->author = $this->author;
            $this->book->published_date = $this->published_date . '-01-01';
            $this->book->status = $this->status;

            $this->book->save();

            if ($this->photo) {
                $this->updateBookPhoto($this->photo, $this->book);
            }


            session()->flash('message', 'Book successfully updated.');
        }

        return redirect()->route('books');
    }
}
