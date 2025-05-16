<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\BookRequest;
use App\Models\Inventory;
use App\Models\ReferenceCode;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class BorrowBook extends Component
{
    public $book_id;
    public $quantity = 0;
    public $remarks;

    public $orderFilter;

    protected $rules = [
        'quantity' => 'required|integer|min:1',
    ];


    public function generateReferenceCode()
    {
        return 'KORLRMDS-' . now()->format('YmdHis');
    }

    public function checkLogin()
    {
        if (session()->has('school_id_expires_at') && now()->lessThan(session('school_id_expires_at'))) {

            $school_id = session('school_id');


            return true;
        } else {
            session()->forget('school_id');
            session()->forget('school_id_expires_at');

            return redirect()->route('login');
        }
    }

    public function submit()
    {
        $this->validate();

        $school_id = session('school_id');


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
            'school_id' => $school_id,
            'book_id' => $this->book_id,
            'remarks' => $this->remarks,
            'quantity' => $this->quantity,
            'reference_id' => $reference->reference_id,
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
        $this->remarks = null;
    }

    public function render()
    {
        return view('livewire.content.borrow-book');
    }
}
