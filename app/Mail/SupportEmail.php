<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $name;
    public $support = false;
    public $subject;
    public $message;
    public $attach;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->email = $request->email;
        $this->name = $request->name;
        $this->support = $request->support;
        $this->subject = $request->subject;
        $this->message = $request->message;
        $this->attach = $request->attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SupportEmail
    {
        return $this->from('waltprorok@gmail.com', 'Support Desk')
            ->subject($this->subject)
            ->markdown('emails.support.ticket');
    }
}
