<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ContactForm extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public object $data,
        public string $type
    ) {}

    public function build(): self
    {
        $mail = $this->from($this->data->email, $this->data->name)
            ->subject($this->data->subject)
            ->markdown('emails.contact.contact')
            ->with([
                'data' => $this->data,
                'type' => $this->type,
            ]);

        if (!empty($this->data->attachment)) {
            $mail->attach(
                Storage::disk('support')->path($this->data->attachment)
            );
        }

        return $mail;
    }
}
