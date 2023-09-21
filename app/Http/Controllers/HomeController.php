<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Mail\ContactForm;
use App\Models\Contact;
use App\Models\Plan;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        return view('marketing.home');
    }

    public function pricing()
    {
        $plans = Plan::all(['id', 'name', 'slug', 'cost']);

        return view('marketing.pricing', compact('plans'));
    }

    public function terms()
    {
        return view('marketing.terms');
    }

    public function privacy()
    {
        return view('marketing.privacy');
    }

    public function faq()
    {
        return view('marketing.faq');
    }

    public function dashboard()
    {
        return view('webapp.index');
    }

    public function contact()
    {
        return view('marketing.contact');
    }

    /**
     * @param StoreContactSubmissionRequest $request
     * @return RedirectResponse
     */
    public function createContact(StoreContactSubmissionRequest $request): RedirectResponse
    {
        try {
            Contact::query()->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'subject' => $request->get('subject'),
                'message' => $request->get('message'),
            ]);

            Mail::to('waltprorok@gmail.com')->send(new ContactForm($request));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return redirect()->route('contact')->with('success', 'The contact form was sent successfully');
    }
}
