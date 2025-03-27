<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;

class ReceiveCopies extends Component
{
    public $date;
    public $time;
    public $quantity = 0;

    public $school_id;
    public $book_id;
    public $inventory_id;

    protected $rules = [
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'quantity' => 'required|integer|min:1',
    ];

    public function recieveCopies()
    {
        // dd("hello");
        $this->validate();

        if($this->inventory_id) {
            $inventory = Inventory::where('inventory_id', $this->inventory_id);
            $inventory->quantity += $this->quantity;
            $inventory->save();

            $this->createTransaction($inventory->inventory_id);

        } else {
            $inventory = Inventory::create([
                'book_id' => $this->book_id,
                'school_id' => $this->school_id,
                'quantity' => $this->quantity,
            ]);

            $this->createTransaction($inventory->inventory_id);
        }

      
        session()->flash('message', 'Copies received successfully.');

        $this->resetFields();

        return redirect()->route('inventory');
    }

    public function createTransaction($inventory_id){
        InventoryTransaction::create([
            'inventory_id' => $inventory_id,
            'transaction_type' => 'received',
            'quantity' => $this->quantity,
            'approved_by' => Auth::user()->id,
            'reference_number' => uniqid(),
            'date' => $this->date,
            'time' => $this->time,
        ]);
    }

    public function resetFields()
    {
        $this->date = null;
        $this->time = null;
        $this->quantity = null;
        $this->school_id = null;
        $this->book_id = null;
    }

    public function render()
    {
        return view('livewire.content.receive-copies');
    }
}
