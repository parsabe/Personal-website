<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $reason;
    public $deletedAt;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $reason, $deletedAt)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->reason = $reason;
        $this->deletedAt = $deletedAt;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('parsabe99@gmail.com', 'Parsa Besharat (Admin)')
                    ->subject('Notice of Account Deletion - Parsa Besharat Portal')
                    ->view('emails.user-deleted');
    }
}
