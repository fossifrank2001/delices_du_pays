<?php

namespace App\Notifications;
use App\Mail\CreatedAccountEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class AccountCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $account, public string $password)
    {
        $this->account = $account;
        $this->password = $password;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'mail',
            'database',
            // 'vonage'
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): Mailable
    {
        return (new CreatedAccountEmail($this->account, $this->password))
            ->to($this->account->CTE_EMAIL); // Use the recipient email from the User model
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'account' => $notifiable,
        ];
    }
    // public function toVonage(): SMS
    // {
    //     // Initialize the Vonage client with your API key and secret
    //     $basic = new Basic("489792c2", "XsMNw8S4inhx5Qf8");
    //     $client = new Client($basic);

    //     // Replace 'VONAGE_PHONE_NUMBER' with the Vonage phone number you want to use as the sender
    //     $from = "237677831959";

    //     // Create the SMS message
    //     $message = new SMS("237675831959", $from, view('sms.createdAccount')->render());

    //     // Send the SMS message
    //     $response = $client->sms()->send($message);
    //     $msg = $response->current();

    //     if ($msg->getStatus() !== 0) {
    //         throw new \Exception("The msg failed with status: " . $msg->getStatus() . "\n");
    //     }

    //     return $message;
    // }
}
