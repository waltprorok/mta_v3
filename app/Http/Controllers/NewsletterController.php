<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsletter;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        if ( ! Newsletter::isSubscribed($request->email) )
        {
            Newsletter::subscribePending($request->email);
            return redirect('/')->with('success', 'Thanks For Subscribing to the News Letter');
        }
        return redirect('/')->with('error', 'Sorry! You have already subscribed ');

    }
}
