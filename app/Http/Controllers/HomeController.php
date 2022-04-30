<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Mail\ContactForm;
use App\Models\Contact;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('marketing.home');
    }

    public function pricing(): View
    {
        $plans = Plan::all();
        return view('marketing.pricing', compact('plans'));
    }

    public function terms(): View
    {
        return view('marketing.terms');
    }

    public function privacy(): View
    {
        return view('marketing.privacy');
    }

    public function faq(): View
    {
        return view('marketing.faq');
    }

    public function dashboard(): View
    {
        return view('webapp.index');
    }

    public function contact(): View
    {
        return view('marketing.contact');
    }

    /**
     * @param StoreContactSubmissionRequest $request
     * @return RedirectResponse
     */
    public function sendContact(StoreContactSubmissionRequest $request): RedirectResponse
    {
        Contact::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'message' => $request->get('message'),
        ]);

        Mail::to('waltprorok@gmail.com')->send(new ContactForm($request));

        return redirect()->route('contact')->with('success', 'The contact form was sent successfully');
    }
}
