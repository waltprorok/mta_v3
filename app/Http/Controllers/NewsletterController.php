<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsletter;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        if (empty($request->input('email'))) {
            return back();
        } else {
            if (!Newsletter::isSubscribed($request->email)) {
                Newsletter::subscribePending($request->email);
                return back()->with('success', 'Thanks For Subscribing to the News Letter');
            }
            return back()->with('error', 'Sorry! You have already subscribed ');
        }


    }
}
