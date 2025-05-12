<?php

namespace App\Livewire\Content;

// Removed unused import
use App\Models\BookRequest;
use Livewire\Component;
use App\Models\User;
use App\Models\BorrowTransaction;
use Carbon\Carbon;
use App\Notifications\BookNotification;

class Notification extends Component
{
    public function getBookRequestNotification()
    {

        $bookRequest = BookRequest::where('status', 'pending')->count();

        return $bookRequest;
    }

    public function checkDue()
    {
        $users = User::all();

        $overdueTransactions = BorrowTransaction::whereIn('status', ['borrowed', 'partially_returned'])
            ->whereHas('transaction.referenceCode.bookRequest', function ($query) {
                $query->whereNotNull('expected_return_date')
                    ->where('expected_return_date', '<', Carbon::today());
            })->get();

        foreach ($overdueTransactions as $dueTransaction) {
            $reference = $dueTransaction->transaction->referenceCode->reference_id ?? null;
            $bookRequest = $reference ? BookRequest::where('reference_id', $reference)->first() : null;
            $school = $bookRequest && $bookRequest->school ? $bookRequest->school->name : 'Unknown School';
            $bookTitle = $dueTransaction->book->title ?? 'Unknown Book';
            $message = "The book '{$bookTitle}', {$school} borrowed is overdue. Please contact specified school to return it as soon as possible.";

            foreach ($users as $user) {
                $user->notify(new BookNotification($message, "Due"));
            }
        }

        BorrowTransaction::whereIn('status', ['borrowed', 'partially_returned'])
            ->whereHas('transaction.referenceCode.bookRequest', function ($query) {
                $query->whereNotNull('expected_return_date')
                    ->where('expected_return_date', '<', Carbon::today());
            })
            ->update(['status' => 'due']);
    }

    public function getDueRequestNotification() {
        return BorrowTransaction::where('status', 'due')->count();
    }

    public function getNotifications()
    {
        $users = User::all();

        $notifications = [];
        foreach ($users as $user) {
            $notifications = array_merge($notifications, $user->unreadNotifications->toArray());
        }

        return $notifications;
    }

    public function readNotification($notifiable_id, $notification_id)
    {
        $user = User::find($notifiable_id);

        $notification = $user->notifications()->where('id', $notification_id)->first();

        if ($notification) {
            $notification->markAsRead();
        }
    }


    public function readAll($notifiable_id)
    {
        $user = User::find($notifiable_id);

        $user->unreadNotifications->markAsRead();
    }
    public function render()
    {
        return view('livewire.content.notification');
    }
}
