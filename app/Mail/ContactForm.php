<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactForm extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $name;
    public $support = false;
    public $subject;
    public $message;
    public $attachment;

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
        $this->attachment = $request->attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): ContactForm
    {
        if ($this->attachment)
            return $this->from($this->email, $this->name)
                ->subject($this->subject)
                ->markdown('emails.contact.contact')
                ->attach($this->attachment->getRealPath(), [
                    'as' => $this->attachment->getClientOriginalName(),
                    'mime' => $this->attachment->getMimeType(),
                ]);
        else {
            return $this->from($this->email, $this->name)
                ->subject($this->subject)
                ->markdown('emails.contact.contact');
        }
    }
}
