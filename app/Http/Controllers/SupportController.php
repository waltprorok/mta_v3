<?php

namespace App\Http\Controllers;

use App\Mail\ContactForm;
use App\Models\Contact;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function index()
    {
        return view('webapp.support.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|min:3',
            'message' => 'required|min:3',
        ]);

        $request['name'] = Auth::user()->getFullNameAttribute();;
        $request['email'] = Auth::user()->email;
        $request['support'] = true;

        try {
            Contact::query()->create([
                'name' => $request['name'],
                'email' => $request['email'],
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
