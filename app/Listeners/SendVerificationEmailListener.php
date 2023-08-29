<?php

namespace App\Listeners;

use App\Events\SendVerificationEmailEvent;
use App\Mail\VerificationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailListener implements ShouldQueue
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
    public function handle(SendVerificationEmailEvent $event)
    {
        // Retrieve the email and verification token from the event
        $email = $event->compte->CTE_EMAIL;
        $verificationToken = $event->verificationToken;

        // Send the verification email using the Mailable
        Mail::to($email, $event->compte->CTE_PRENOM .' '. $event->compte->CTE_NOM)
            ->send(new VerificationEmail($event->compte, $verificationToken));
    }
}
