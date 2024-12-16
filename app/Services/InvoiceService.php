<?php

namespace App\Services;

use App\Mail\LessonsInvoice;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{

    public function __construct()
    {
        //
    }

    /**
     * @param $invoice
     * @param $additionalEmail
     * @return void
     */
    public function emailInvoiceToStudentOrParent($invoice, $additionalEmail): void
    {
        // student does not have email but parent does have email
        if (is_null($invoice->student->email) && $additionalEmail) {
            Mail::to($additionalEmail)->queue(new LessonsInvoice($invoice));
        } // just parent has email
        elseif ($additionalEmail) {
            Mail::to($additionalEmail)->queue(new LessonsInvoice($invoice));
        } // just the student has an email
        elseif (! is_null($invoice->student->email) && is_null($additionalEmail)) {
            Mail::to($invoice->student->email)->queue(new LessonsInvoice($invoice));
        } // student and parent have an email
        elseif (! is_null($invoice->student->email && ! is_null($additionalEmail))) {
            Mail::to($invoice->student->email)->cc($additionalEmail)->queue(new LessonsInvoice($invoice));
        }
    }

}
