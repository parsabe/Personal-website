<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $bodyText;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectLine, $bodyText)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyText = $bodyText;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('noreply@parsabe.com', 'Parsa Besharat')
                    ->subject($this->subjectLine)
                    ->html(nl2br(e($this->bodyText)));
    }
}
