<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreatedAccountEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $account, public string $password)
    {
        $this->account = $account;
        $this->password = $password;
    }

    /**
     * Build the message. 'emails.alertAdminForNewAccount', ['account' => $this->account]
     */
    public function build(): self
    {
        return $this->subject('USER CREDENTIALS FOR CONNEXION')
            ->markdown('emails.createdAccount', ['account' => $this->account, 'password' => $this->password]);
    }
}
