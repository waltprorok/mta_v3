<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContactSubmission;
use App\Mail\ContactForm;
use App\Plan;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('marketing.home');
    }

    public function pricing()
    {
        $plans = Plan::all();
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

    public function blog()
    {
        return view('marketing.blog');
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

    public function sendContact(StoreContactSubmission $request)
    {
        $contact = new Contact([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'message' => $request->get('message'),
        ]);

        $contact->save();

        Mail::to('waltprorok@gmail.com')->send(new ContactForm($request));

        return redirect()->route('contact')->with('success', 'The contact form was sent successfully');
    }
}
