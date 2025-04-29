<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\BookRequest;
use App\Models\Inventory;
use App\Models\ReferenceCode;
use App\Models\School;
use App\Models\Book;
use App\Models\BorrowTransaction;

class BorrowRequestManagement extends Component
{
    public $book_id;
    public $school_id;
    public $quantity = 0;
    public $books = [];
    public $schools = [];
    public $remarks;
    public $delivered_quantity;

    protected $rules = [
        'quantity' => 'required|integer|min:1',
        'book_id' => 'required|exists:books,book_id',
        'school_id' => 'required|exists:schools,school_id',
        'remarks' => 'required|string|max:255',
    ];


    public function generateReferenceCode()
    {
        return 'KORLRMDS-' . now()->format('YmdHis');
    }
    public function submit()
    {
        $this->validate();

        $inventory = Inventory::where('book_id', $this->book_id)
            ->where('location_type', 'division')
            ->first();

        if (!$inventory || $inventory->quantity < $this->quantity) {
            $this->addError('quantity', 'The quantity must not be larger than the quantity of the requested book.');
            return;
        }

        $reference = ReferenceCode::create([
            'reference_code' => $this->generateReferenceCode()
        ]);

        BookRequest::create([
            'school_id' => $this->school_id,
            'book_id' => $this->book_id,
            'remarks' => $this->remarks,
            'quantity' => $this->quantity,
            'reference_id' => $reference->reference_id,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Book request created successfully.');

        $this->resetFields();

        return redirect()->route('book-request');
    }

    public function resetFields()
    {
        $this->school_id = null;
        $this->book_id = null;
        $this->quantity = null;
        $this->remarks = null;
    }

    public function mount()
    {
        $this->books = Book::where('status', 'available')
            ->whereHas('divisionInventory', function ($query) {
                $query->where('quantity', '>', 0);
            })->get();
        $this->schools = School::where('status', 'active')->get();
    }
    public function render()
    {
        return view('livewire.content.borrow-request-management');
    }
}
