<?php

namespace App\Livewire\Content;

use App\Models\Book;
use App\Models\BookRequest;
use Livewire\Component;

class Notification extends Component
{
    public function getBookRequestNotification() {
        
        $bookRequest = BookRequest::where('status', 'pending')->count();

        return $bookRequest;
    }
    public function render()
    {
        return view('livewire.content.notification');
    }
}
