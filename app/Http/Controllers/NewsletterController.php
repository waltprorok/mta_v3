<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Newsletter;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        try {
            if (! Newsletter::isSubscribed($email)) {
                Newsletter::subscribePending($email);

                return back()->with('success', 'Thank you for subscribing to the newsletter!');
            }

            return back()->with('error', 'You are already subscribed.');
        } catch (Exception $e) {
            Log::error('Newsletter subscription error: ' . $e->getMessage());

            return back()->with('error', 'There was a problem subscribing you to the newsletter. Please try again later.');
        }
    }
}
