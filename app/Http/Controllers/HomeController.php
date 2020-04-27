<?php

namespace App\Http\Controllers;

use App\Mail\ContactForm;
use Illuminate\Http\Request;
use App\Plan;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
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

    public function contact(Request $request)
    {
        return view('marketing.contact');
    }

    public function sendContact(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:3',
            'message' => 'required|min:3',
        ]);

        Mail::to('waltprorok@gmail.com')->send(new ContactForm($request));
        return redirect()->route('contact')->with('success', 'The contact form was sent successfully');
    }


}
