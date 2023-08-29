<?php

namespace App\Notifications;

use App\Mail\alertAdminForNewAccountMail;
use App\Mail\CreatedAccountEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotificationForNewAccount extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $account)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): Mailable
    {
        return (new alertAdminForNewAccountMail($this->account))
            ->to($notifiable->CTE_EMAIL);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'account' => $this->account, // Corrected variable name
        ];
    }
}
