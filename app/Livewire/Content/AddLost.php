<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;

class AddLost extends Component
{

    public $date;
    public $time;
    public $quantity;

    public $inventory_id;

    protected $rules = [
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'quantity' => 'required|integer|min:1',
    ];

    public function addLost()
    {
        $this->validate();

        $inventory = Inventory::find($this->inventory_id);

        if ($this->quantity > $inventory->quantity) {
            $this->addError('quantity', 'The quantity must not be larger than the quantity in the current inventory.');
            return;
        }

        $inventory->quantity -= $this->quantity;
        $inventory->save();

        InventoryTransaction::create([
            'inventory_id' => $inventory->inventory_id,
            'transaction_type' => 'lost',
            'quantity' => $this->quantity,
            'approved_by' => Auth::user()->id,
            'reference_number' => uniqid(),
            'date' => $this->date,
            'time' => $this->time,
        ]);

        session()->flash('message', 'Lost copies added successfully.');

        $this->resetFields();

        return redirect()->route('inventory');
    }

    public function resetFields()
    {
        $this->date = null;
        $this->time = null;
        $this->quantity = null;
        $this->inventory_id = null;
    }
    
    public function render()
    {
        return view('livewire.content.add-lost');
    }
}
