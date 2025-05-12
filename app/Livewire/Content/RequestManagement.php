<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Models\BookRequest;
use App\Models\BorrowTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;
use App\Models\Transaction;

class RequestManagement extends Component
{
    public $request_id;
    public $delivered_quantity;
    public $status;

    public $school_id;
    public $book_id;
    public $quantity;

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

        $bookRequest = BookRequest::where('request_id', $this->request_id)->first();

        if ($bookRequest) {
            if ($this->delivered_quantity > $bookRequest->quantity) {
                $this->addError('delivered_quantity', 'The quantity must not be larger than the quantity of the requested book.');
                return;
            }
            
            $bookRequest->quantity_released = $this->delivered_quantity;
            $bookRequest->status = 'approved';
            $bookRequest->approved_by = Auth::user()->id;
            $bookRequest->save();

            session()->flash('message', 'Request updated successfully.');
        } else {
            session()->flash('error', 'Request not found.');
        }

        $this->updateInventory();

        return redirect()->route('book-request');
    }

    public function updateInventory() {
        $bookRequest = BookRequest::where('request_id', $this->request_id)->first();
        if($bookRequest){
           
            $inventory = Inventory::where('book_id', $bookRequest->book_id)
                ->where('location_id', $bookRequest->school_id)
                ->where('location_type', 'school')
                ->first();

            if($inventory){
                $inventory->quantity += $this->delivered_quantity;
                $inventory->save();
            } else {
                $inventory = Inventory::create([
                    'book_id' => $bookRequest->book_id,
                    'location_id' => $bookRequest->school_id,
                    'location_type' => 'school',
                    'quantity' => $this->delivered_quantity,
                ]);
            }

            $this->updateDivisionInventory();
            $this->createInventoryTransaction($inventory->inventory_id);
        }
    }

    public function updateDivisionInventory() 
    {
        $bookRequest = BookRequest::where('request_id', $this->request_id)->first();
        $inventory = Inventory::where('book_id', $bookRequest->book_id)
            ->where('location_type', 'division')
            ->first();

        if($inventory) {
            if ($this->delivered_quantity > $inventory->quantity) {
                $this->addError('delivered_quantity', 'The quantity must not be larger than the available quantity in the inventory. Current available quantity: ' . $inventory->quantity);
                return;
            }
            $inventory->quantity -= $this->delivered_quantity;
            $inventory->save();
        } 
    }

    public function createBorrowTransaction($transaction_id) 
    {
        $bookRequest = BookRequest::where('request_id', $this->request_id)->first();
        
        BorrowTransaction::create([
            'book_id' => $bookRequest->book_id,
            'user_id' => $bookRequest->approved_by,
            'transaction_id' => $transaction_id,
            'borrow_timestamp' => now(),
            'return_date' => null, // or set a specific return date if available
            'quantity_lost' => 0,
            'status' => 'borrowed',
        ]);

    }

    public function createInventoryTransaction($inventory_id)
    {
        $bookRequest = BookRequest::where('request_id', $this->request_id)->first();
        
        $transaction = Transaction::create([
            'inventory_id' => $inventory_id,
            'transaction_type' => 'receive',
            'quantity' => $this->delivered_quantity,
            'approved_by' => Auth::user()->id,
            'reference_id' => $bookRequest->reference_id,
            'transaction_timestamp' => now(),
        ]);

        $this->createBorrowTransaction($transaction->transaction_id);
    }

    public function resetFields()
    {
        $this->request_id = null;
        $this->delivered_quantity = null;
        $this->expected_return_date = null;
    }

    public function render()
    {
        return view('livewire.content.request-management');
    }
}
