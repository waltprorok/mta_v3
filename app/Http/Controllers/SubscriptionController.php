<?php

namespace App\Http\Controllers;

use App\Mail\SubscribedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
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
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];
        $user->save();

        if ($request['current_password'] != "") {
            if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
                return redirect()->back()->with('error', 'Your current password does not match with the password you provided. Please try again.');
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
