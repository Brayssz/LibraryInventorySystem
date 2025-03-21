<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\BookRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryTransaction;
use App\Models\Inventory;

class RequestManagement extends Component
{
    public $request_id;
    public $delivered_quantity;
    public $status;

    protected $rules = [
        'delivered_quantity' => 'required|integer|min:1',
    ];

    public function approveRequest()
    {
        $this->status = 'approved';
    }

    public function rejectRequest()
    {
        $this->status = 'rejected';
    }

    public function updateRequest()
    {
        $this->validate();


        $bookRequest = BookRequest::find($this->request_id);
        if ($bookRequest) {
            if ($this->delivered_quantity > $bookRequest->quantity) {
                $this->addError('delivered_quantity', 'The quantity must not be larger than the quantity of the requested book.');
                return;
            }
            
            $bookRequest->delivered_quantity = $this->delivered_quantity;
            $bookRequest->status = 'approved';
            $bookRequest->approved_by = Auth::user()->id;
            $bookRequest->save();

            session()->flash('message', 'Request updated successfully.');
        } else {
            session()->flash('error', 'Request not found.');
        }

        $this->updateInventory();

        $this->resetFields();

        return redirect()->route('book-request');
    }

    public function updateInventory() {
        $bookRequest = BookRequest::find($this->request_id);
        if($bookRequest){
           
            $inventory = Inventory::where('book_id', $bookRequest->book_id)
                ->where('school_id', $bookRequest->school_id)
                ->first();

            if($inventory){
                $inventory->quantity += $this->delivered_quantity;
                $inventory->save();
            } else {
                $inventory = Inventory::create([
                    'book_id' => $bookRequest->book_id,
                    'school_id' => $bookRequest->school_id,
                    'quantity' => $this->delivered_quantity,
                ]);
            }

            $this->createInventoryTransaction($inventory->inventory_id);
        }
    }

    public function createInventoryTransaction($inventory_id)
    {
        InventoryTransaction::create([
            'inventory_id' => $inventory_id,
            'transaction_type' => 'received',
            'quantity' => $this->delivered_quantity,
            'approved_by' => Auth::user()->id,
        ]);
    }

    public function resetFields()
    {
        $this->request_id = null;
        $this->delivered_quantity = null;
    }

    public function render()
    {
        return view('livewire.content.request-management');
    }
}
