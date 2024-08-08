<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;


class LessonsInvoice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return LessonsInvoice|null
     */
    public function build(): ?LessonsInvoice
    {
        $pdfFileExists = Storage::disk('invoice')->exists('Invoice_MTA_' . $this->invoice->id . '.pdf');

        if ($pdfFileExists) {
            $pdfInvoiceName = 'Invoice_MTA_' . $this->invoice->id . '.pdf';

            return $this->subject('Music Teachers Aid | Lessons Invoice')
                ->markdown('emails.invoice.lessons')
                ->attach(public_path('/storage/invoice'), [
                    'as' => $pdfInvoiceName,
                    'mime' => 'application/pdf',
                ]);
        }

        return null;
    }
}
