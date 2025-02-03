<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class StoreInvoicePDFJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?string $additionalEmail;

    protected Invoice $invoice;

    protected InvoiceService $invoiceService;

    public function __construct(Invoice $invoice, $additionalEmail = null)
    {
        $this->invoice = $invoice;
        $this->additionalEmail = $additionalEmail;
        $this->invoiceService = new InvoiceService();
    }

    /**
     * @return mixed
     */
    public function handle(): mixed
    {
        $invoiceWithRelations = $this->invoiceService->getInvoiceStudentTeacherBillingRate($this->invoice);

        $invoice = $this->invoiceService->getCalculatedLessonTotals($invoiceWithRelations);

        $pdf = app(PDF::class);
        $pdf->setPaper('A4');
        $pdf->loadView('webapp.invoice.pdf_view', ['invoice' => $invoice]);

        Storage::disk('invoice')->put('Invoice_MTA_' . $invoice->id . '.pdf', $pdf->output());

        $this->invoiceService->emailInvoiceToStudentOrParent($invoice, $this->additionalEmail);

        return $invoice;
    }
}
