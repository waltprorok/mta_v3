<?php

namespace App\Http\Controllers;

use App\Mail\SubscribedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Plan;

class SubscriptionController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user->subscribed('premium'))
        {
            return view('webapp.account.subscription');
        } else {
            $plans = Plan::all();
            return view('webapp.account.index', compact('plans'));
        }
    }

    public function subscribed()
    {
        return view('webapp.account.subscription');
    }

    public function create(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $plan = Plan::findOrFail($request->get('plan'));

        $request->user()
            ->newSubscription($plan->slug, $plan->stripe_plan)
            ->create($request->stripeToken);

        Mail::to($user->email)->send(new SubscribedMail($user));

        return redirect()->back()->with('success', 'Thank you for subscribing to our service.');
    }

    public function cancel()
    {
        $user = Auth::user();
        $subscription = $user->subscription('premium');
        $subscription->cancel();

        return redirect()->back()->with('warning', 'Your subscription account has been cancelled');
    }

    public function resume()
    {
        $user = Auth::user();
        $subscription = $user->subscription('premium');
        $subscription->resume();

        return redirect()->back()->with('success', 'Your subscription account has been reinstated');
    }

    public function creditCard() {
        return view('webapp.account.card');
    }

    public function updateCreditCard(Request $request) {
        $user = Auth::user();
        $ccToken = $request->input('stripeToken');
        $user->updateCard($ccToken);
        return redirect()->back()->with(['success' => 'Credit card updated successfully.']);
    }

    public function pdfDownload($invoiceId)
    {
        $user = Auth::user();
        return $user->downloadInvoice($invoiceId, [
            'vendor' => 'Music Teachers Aid',
            'product'=> 'Premium Subscription'
        ]);
    }

    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->invoices();
        return view('webapp.account.invoices', compact('invoices'));
    }

}
