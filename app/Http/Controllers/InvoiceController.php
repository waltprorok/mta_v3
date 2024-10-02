<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\InvoicePaymentRequest;
use App\Mail\LessonsInvoice;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\PaymentType;
use App\Models\Student;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function downloadPDF(Invoice $id)
    {
        $invoice = $this->getInvoiceStudentTeacherBillingRate($id);

        if (is_null($invoice)) {
            return null;
        }

        $pdf = app(PDF::class);
        $pdf->setPaper('A4');

        $pdfFileExists = Storage::disk('invoice')->exists('Invoice_MTA_' . $invoice->id . '.pdf');

        $pdfFile = $pdf->loadView('webapp.invoice.pdf_view', ['invoice' => $invoice]);

        if (! $pdfFileExists) {
            Storage::disk('invoice')->put('Invoice_MTA_' . $invoice->id . '.pdf', $pdfFile->output());
        }

        return $pdfFile->download('Invoice_MTA_' . $invoice->id . '.pdf');
    }

    public function storePDF(Invoice $id)
    {
        $invoice = $this->getInvoiceStudentTeacherBillingRate($id);

        $pdf = app(PDF::class);
        $pdf->setPaper('A4');
        $pdf->loadView('webapp.invoice.pdf_view', ['invoice' => $invoice]);

        Storage::disk('invoice')->put('Invoice_MTA_' . $invoice->id . '.pdf', $pdf->output());

        return $invoice;
    }

    public function index(): JsonResponse
    {
        $students = Invoice::with('student', 'teacher')
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($students);
    }

    public function getStudentSelected(int $id): JsonResponse
    {
        $student = Student::where('student_id', $id)
            ->with('lessons:id,student_id,teacher_id,billing_rate_id,invoice_id,start_date,end_date,complete')
            ->with('lessons.billingRate')
            ->with('lessons.invoice')
            ->first();

        $studentTeacher = Student::where('student_id', $id)
            ->with('getTeacher')
            ->first();

        $filteredLessons = $student->lessons->filter(function ($lesson) {
            return is_null($lesson->invoice);
        })->values();

        $lastInvoice = Invoice::where('student_id', $student->id)->orderBy('created_at', 'desc')->first();

        return response()->json(['lessons' => $filteredLessons, 'studentTeacher' => $studentTeacher, 'lastInvoice' => $lastInvoice]);
    }

    public function createInvoice()
    {
        // TODO: get by month
        $student = Student::query()
            ->where('status', Student::ACTIVE)
            ->where('teacher_id', Auth::id())
            ->with('lessons:id,student_id,teacher_id,billing_rate_id,start_date,end_date,complete')
            ->orderBy('first_name')
            ->get();

        return response()->json($student);
    }

    public function show(int $id): View
    {
        // TODO: get by month
        $invoice = Invoice::with('student', 'student.getTeacher')
            ->where('id', $id)
            ->firstOrFail();

        $lessonIds = explode(',', $invoice->lesson_id);
        $lessons = Lesson::whereIn('id', $lessonIds)->withTrashed()->get();

        $subTotal = 0;
        $discount = $invoice->discount;
        $total = 0;

        // 1. calculate totals first
        foreach ($lessons as $lesson) {
            if ($lesson->billingRate->type == 'lesson') {
                $subTotal += $lesson->billingRate->amount;
                $total += $lesson->billingRate->amount;
            }

            if ($lesson->billingRate->type == 'hourly') {
                $minutes = $lesson->interval / 60;
                $subTotal += $lesson->billingRate->amount * $minutes;
                $total += $lesson->billingRate->amount * $minutes;
            }

            if ($lesson->billingRate->type == 'monthly') {
                $subTotal += $lesson->billingRate->amount;
                $total += $lesson->billingRate->amount;
                break;
            }
        }

        // 2. calculate each lesson amount
        $lessons->map(function ($lesson) use ($lessons) {
            if ($lesson->billingRate->type == 'hourly') {
                $minutes = $lesson->interval / 60;
                $amount = $lesson->billingRate->amount * $minutes;
                return [
                    $lesson->billingRate->amount = $amount,
                ];
            }

            if ($lesson->billingRate->type == 'monthly') {
                $amount = $lesson->billingRate->amount / count($lessons);
                return [
                    $lesson->billingRate->amount = $amount,
                ];
            }

            return $lesson;
        });

        $subTotalCalculation = $subTotal * ($discount / 100);
        $total = $total - $subTotalCalculation;

        if ($invoice->is_paid && $invoice->balance_due == 0) {
            $balanceDue = $invoice->balance_due;
        } else {
            $balanceDue = $total;
        }

        return view('webapp.invoice.show', compact('invoice', 'lessons', 'subTotal', 'discount', 'total', 'balanceDue'));
    }

    public function store(Request $request)
    {
        $lessonIds = explode(',', $request->lesson_id);
        $additionalEmail = $request->get('additional_email') ?? null;

        try {
            $newInvoice = Invoice::query()->create($request->all());

            foreach ($lessonIds as $lessonId) {
                Lesson::query()->findOrFail($lessonId)->update(['invoice_id' => $newInvoice->id]);
            }

            $invoice = $this->storePDF($newInvoice);

            if ($additionalEmail) {
                Mail::to($invoice->student->email)->cc($additionalEmail)->send(new LessonsInvoice($invoice));
            } else {
                Mail::to($invoice->student->email)->send(new LessonsInvoice($invoice));
            }

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * @param InvoicePaymentRequest $request
     * @param Invoice $invoice
     * @return JsonResponse
     */
    public function update(InvoicePaymentRequest $request, Invoice $invoice): JsonResponse
    {
        $isPaid = $invoice->balance_due - $request->get('payment') == 0;

        try {
            $invoice->update([
                'balance_due' => $invoice->balance_due - $request->get('payment'),
                'payment' => $request->get('payment'),
                'payment_type_id' => $request->get('payment_type_id'),
                'check_number' => $request->get('check_number'),
                'payment_information' => $request->get('payment_information'),
                'is_paid' => $isPaid,

            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        try {
            Mail::to($invoice->student->email)->send(new LessonsInvoice($invoice));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json();
    }

    private function getInvoiceStudentTeacherBillingRate(Invoice $id)
    {
        return Invoice::with('student.getTeacher')
            ->with('lessons.billingRate')
            ->where('teacher_id', Auth::id())
            ->findOrFail($id->id);
    }

    public function getListOfPayments()
    {
        $payments = Invoice::with('paymentType:id,name')
            ->where('is_paid', true)
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'teacher_id',
                'payment_type_id',
                'payment',
                'discount',
                'total',
                'subtotal',
                'balance_due',
                'payment_information',
                'check_number',
                'is_paid',
                'updated_at',
            ]);

        return response()->json($payments);
    }

    public function getPaymentTypes(): JsonResponse
    {
        $paymentTypes = PaymentType::get(['id', 'name']);

        return response()->json($paymentTypes);
    }
}
