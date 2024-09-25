<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Mail\ContactForm;
use App\Models\Contact;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function index()
    {
        return view('webapp.support.index');
    }

    public function store(StoreContactSubmissionRequest $request): RedirectResponse
    {
        try {
            Contact::query()->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'subject' => 'SUPPORT: ' . $request->get('subject'),
                'message' => $request->get('message'),
            ]);

            Mail::to('waltprorok@gmail.com')->send(new ContactForm($request));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return redirect()->route('support')->with('success', 'The contact form was sent successfully');
    }
}
