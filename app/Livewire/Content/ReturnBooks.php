<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\BorrowTransaction;
use App\Models\ReturnTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;

class ReturnBooks extends Component
{
    public $borrow_id;
    public $school_id;
    
    public $date;
    public $time;
    public $quantity = 0;

    protected $rules = [
        'date' => 'required|date|before_or_equal:today',
        'time' => 'required|date_format:H:i',
        'quantity' => 'required|integer|min:1',
    ];

    public function returnBooks()
    {
        $borrowTransaction = BorrowTransaction::where('borrow_id', $this->borrow_id)->with('returnTransactions', 'transaction')->first();
        
        $totalReturned = $borrowTransaction->returnTransactions? $borrowTransaction->returnTransactions->sum('quantity') : 0;
        $totalBorrowed = $borrowTransaction->transaction->quantity;
        $remaining = $totalBorrowed - $totalReturned;

        $borrowTransaction->return_date = Carbon::parse($this->date . ' ' . $this->time);
        $borrowTransaction->quantity_lost = $remaining;
        $borrowTransaction->user_id = Auth::user()->id;
        $borrowTransaction->status = 'returned';
        $borrowTransaction->save();

        $this->updateSchoolInventory($borrowTransaction->book_id, $remaining);

        session()->flash('message', 'Book returned successfully.');

        return redirect()->route('borrowed-books');
    }

    public function updateSchoolInventory($book_id, $quantity) 
    {
        $inventory = Inventory::where('book_id', $book_id)->where('location_id', $this->school_id)->where('location_type', 'school')->first();
        $inventory->quantity -= $quantity;
        $inventory->save();
    }

    public function updateDivisionInventory($book_id, $quantity) 
    {
        $inventory = Inventory::where('book_id', $book_id)->where('location_type', 'division')->first();
        $inventory->quantity += $quantity;
        $inventory->save();
    }

    public function returnPartial(){

        $this->validate();

        $borrowTransaction = BorrowTransaction::where('borrow_id', $this->borrow_id)->with('returnTransactions', 'transaction')->first();
        
        $totalReturned = $borrowTransaction->returnTransactions? $borrowTransaction->returnTransactions->sum('quantity') : 0;
        $totalBorrowed = $borrowTransaction->transaction->quantity;
        $remaining = $totalBorrowed - $totalReturned;

        if($this->quantity > $remaining){
            $this->addError('quantity', 'The quantity must not be larger than the remaining quantity of the borrowed book.');
            return;
        }

        $borrowTransaction->status = 'partially_returned';
        $borrowTransaction->save();

        $borrowTransaction->returnTransactions()->create([
            'quantity' => $this->quantity,
            'recorded_by' => Auth::id(),
            'return_date' => Carbon::parse($this->date . ' ' . $this->time),
        ]);
        
        $this->updateSchoolInventory($borrowTransaction->book_id, $this->quantity);
        $this->updateDivisionInventory($borrowTransaction->book_id, $this->quantity);

        session()->flash('message', 'Book returned successfully.');

        return redirect()->route('borrowed-books');
    }

    public function resetFields()
    {
        $this->date = null;
        $this->time = null;
        $this->quantity = 0;
    }

    public function render()
    {
        return view('livewire.content.return-books');
    }
}
