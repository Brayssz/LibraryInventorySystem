<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\BookRequest;

class BorrowBook extends Component
{
    public $book_id;
    public $quantity;

    protected $rules = [
        'quantity' => 'required|integer|min:1',
    ];

    public function submit()
    {
        $this->validate();

        $school_id = session('school_id');

        BookRequest::create([
            'school_id' => $school_id,
            'book_id' => $this->book_id,
            'quantity' => $this->quantity,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Book request created successfully.');

        $this->resetFields();

        return redirect()->route('request-form');
    }

    public function resetFields()
    {
        $this->book_id = null;
        $this->quantity = null;
    }

    public function render()
    {
        return view('livewire.content.borrow-book');
    }
}
