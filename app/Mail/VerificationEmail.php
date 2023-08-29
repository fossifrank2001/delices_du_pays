<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public $compte, public String $verificationToken)
    {
        $this->compte = $compte;
        $this->verificationToken = $verificationToken;
    }

    public function envelop():Envelope
    {
        // return $this->markdown('emails.verificationEmailForRegistration');
        return new Envelope(
            subject: 'EMAIL FOR VERIFICATION ACCOUNT',
        ); // Remplacez par le nom de la vue que vous souhaitez utiliser pour le corps de l'e-mail
    }

    public function content():Content
    {
        return new Content(
            markdown: 'emails.verificationEmailForRegistration'
        );
    }
}

