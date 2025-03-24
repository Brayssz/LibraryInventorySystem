<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Inventory;
use App\Models\ReferenceCode;
use Illuminate\Support\Facades\Auth;


class ReceiveDelivery extends Component
{
    public $date;
    public $time;

    public $book_id;
    public $quantity = 0;

    protected $rules = [
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'quantity' => 'required|integer|min:1',
    ];

    public function receiveDelivery()
    {
        $this->validate();

        $inventory = Inventory::where('book_id', $this->book_id)
            ->where('location_type', 'division')
            ->first();

        if (!$inventory) {
            $inventory = Inventory::create([
                'book_id' => $this->book_id,
                'location_type' => 'division',
                'quantity' => $this->quantity,
            ]);
        } else {
            $inventory->quantity += $this->quantity;
            $inventory->save();
        }

        $reference = ReferenceCode::create([
            'reference_code' => $this->generateReferenceCode()
        ]);

        Transaction::create([
            'inventory_id' => $inventory->inventory_id,
            'quantity' => $this->quantity,
            'transaction_type' => 'delivery',
            'approved_by' => Auth::user()->id, 
            'reference_id' => $reference->reference_id, 
            'transaction_timestamp' => now(),
        ]);

        session()->flash('message', 'Delivery received successfully.');

        $this->resetFields();

        return redirect()->route('inventory');
    }

    public function generateReferenceCode()
    {
        
        return 'KORLRMDS-' . now()->format('YmdHis');
    }

    public function resetFields()
    {
        $this->date = null;
        $this->time = null;
        $this->quantity = null;
    }

    public function render()
    {
        return view('livewire.content.receive-delivery');
    }
}
