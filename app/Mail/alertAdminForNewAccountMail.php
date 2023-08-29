<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class alertAdminForNewAccountMail extends Mailable implements  ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $compte)
    {
        $this->compte = $compte;
    }

    public function build(): self
    {
        return $this->subject('NEW USER NOTIFICATION')
            ->markdown('emails.alertAdminForNewAccount', ['account' => $this->compte]);
    }
}
