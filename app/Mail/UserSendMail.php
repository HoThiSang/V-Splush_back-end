<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSendMail extends Mailable
{
    use Queueable, SerializesModels;
    public  $sendData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendData)
    {
        $this->sendData = $sendData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.send-mail')
            ->with([
                'userName' => $this->sendData['name'],
                'title' => $this->sendData['subject'],
                'content' => $this->sendData['message'],
            ]);
    }
}
