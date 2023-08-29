<?php

namespace App\Listeners;

use App\Events\ResetPasswordEmailEvent;
use App\Mail\ResetPasswordEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ResetPasswordEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(ResetPasswordEmailEvent $event)
    {
        // Retrieve the email and verification token from the event
        $token = $event->token;
        $email = $event->email;

        // Send the verification email using the Mailable
        Mail::to($email)
            ->send(new ResetPasswordEmail($token));
    }
}
