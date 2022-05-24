<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Newsletter;

class NewsletterController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (empty($request->input('email'))) {
            return back();
        } else {
            if (! Newsletter::isSubscribed($request->get('email'))) {
                Newsletter::subscribePending($request->get('email'));

                return back()->with('success', 'Thank You For Subscribing to the News Letter');
            }

            return back()->with('error', 'Sorry! You have already subscribed');
        }
    }
}
