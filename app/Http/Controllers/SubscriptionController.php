<?php

namespace App\Http\Controllers;

use App\Mail\SubscribedMail;
use App\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    protected $receiptLimit = 5;

    public function index()
    {
        $user = Auth::user();

        if ($user->subscriptions()->first() != null) {
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

    /**
     * @param Request $request
     * @param Plan $plan
     * @return RedirectResponse
     */
    public function create(Request $request, Plan $plan)
    {
        $user = Auth::user();

        $plan = Plan::findOrFail($request->get('plan'));

        $request->user()->newSubscription($plan->slug, $plan->stripe_plan)->create($request->stripeToken);

        Mail::to($user->email)->send(new SubscribedMail($user));

        return redirect()->back()->with('success', 'Thank you for subscribing to our service.');
    }

    /**
     * @return RedirectResponse
     */
    public function cancel(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->subscription('premium')) {
            $subscription = $user->subscription('premium');
        } elseif ($user->subscription('enterprise')) {
            $subscription = $user->subscription('enterprise');
        }

        $subscription->cancel();

        return redirect()->back()->with('warning', 'Your subscription account has been cancelled');
    }

    /**
     * @return RedirectResponse
     */
    public function resume(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->subscription('premium')) {
            $subscription = $user->subscription('premium');
        } elseif ($user->subscription('enterprise')) {
            $subscription = $user->subscription('enterprise');
        }

        $subscription->resume();

        return redirect()->back()->with('success', 'Your subscription account has been reinstated');
    }

    public function creditCard()
    {
        return view('webapp.account.card');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateCreditCard(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $ccToken = $request->input('stripeToken');
        $user->updateCard($ccToken);

        return redirect()->back()->with(['success' => 'Credit card updated successfully.']);
    }

    /**
     * @param $invoiceId
     * @return mixed
     */
    public function pdfDownload($invoiceId)
    {
        $user = Auth::user();
        $subscriptionName = ucfirst($user->subscriptions->first()->name);

        return $user->downloadInvoice($invoiceId, [
            'vendor' => 'Music Teachers Aid',
            'product' =>  $subscriptionName
        ]);
    }

    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->invoices();

        return view('webapp.account.invoices', compact('invoices'));
    }

    public function profile()
    {
        return view('webapp.account.profile');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
        ]);

        $user = Auth::user();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->save();

        if ($request->get('current_password') != "") {
            if (! (Hash::check($request->get('current_password'), Auth::user()->password))) {
                return redirect()->back()->with('error', 'Your current password does not match with the new password you provided. Please try again.');
            }

            if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
                return redirect()->back()->with('error', 'New Password cannot be same as your current password. Please choose a different password.');
            }

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $user->password = bcrypt($request->get('new_password'));
            $user->save();

            return redirect()->back()->with('success', 'Password changed successfully!');
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
