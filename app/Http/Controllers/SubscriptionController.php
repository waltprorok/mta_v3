<?php

namespace App\Http\Controllers;

use App\Mail\SubscribedMail;
use App\Models\Plan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    protected $receiptLimit = 5;

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->subscriptions()->first() !== null) {
            return view('webapp.account.subscription');
        } else {
            $plans = Plan::all();
            return view('webapp.account.index', compact('plans'));
        }
    }

    /**
     * @return Application|Factory|View
     */
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
        $teacher = Auth::user()->getTeacher()->first();

        $plan = Plan::findOrFail($request->get('plan'));

        $request->user()->newSubscription($plan->name, $plan->stripe_plan)
            ->create($request->stripeToken, [
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'address' => [
                    'line1' => $teacher->address,
                    'line2' => $teacher->address_2,
                    'city' => $teacher->city,
                    'state' => $teacher->state,
                    'postal_code' => $teacher->zip,
                ],
                'phone' => $teacher->phone,
            ]);

        Mail::to($teacher->email)->send(new SubscribedMail($teacher));

        return redirect()->back()->with('success', 'Thank you for subscribing to our service.');
    }

    public function listPlanChange()
    {
        $plans = Plan::all();
        $user = Auth::user();

        foreach ($plans as $plan) {
            if ($plan->stripe_plan == $user->subscription('premium')->stripe_plan) {
                switch ($plan->id) {
                    case 1:
                        $plan = Plan::findOrFail(2);
                        break;
                    case 2:
                        $plan = Plan::findOrFail(1);
                        break;
                    default:
                        break;
                }
            }
        }

        return view('webapp.account.change', compact('plan'));
    }

    /**
     * @return RedirectResponse
     */
    public function changePlan(): RedirectResponse
    {
        $plans = Plan::all();
        $user = Auth::user();

        foreach ($plans as $plan) {
            if ($plan->stripe_plan == $user->subscription('premium')->stripe_plan) {
                if ($plan->id == 1) {
                    $newPlan = Plan::findOrFail(2);
                    $user->subscription('premium')->swap($newPlan->stripe_plan);
                    break;
                } elseif ($plan->id == 2) {
                    $newPlan = Plan::findOrFail(1);
                    $user->subscription('premium')->swap($newPlan->stripe_plan);
                    break;
                }
            }
        }

        return redirect()->back()->with('success', 'Your subscription plan has been updated.');
    }

    /**
     * @return RedirectResponse
     */
    public function cancel(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->subscription('premium')) {
            $subscription = $user->subscription('premium');
            $subscription->cancel();
        }

        return redirect()->back()->with('warning', 'Your subscription account has been cancelled.');
    }

    /**
     * @return RedirectResponse
     */
    public function resume(): RedirectResponse
    {
        $user = Auth::user();

        $subscription = $user->subscription('premium');

        $subscription->resume();

        return redirect()->back()->with('success', 'Your subscription account has been reinstated.');
    }

    /**
     * @return Application|Factory|View
     */
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

        return redirect()->back()->with('success', 'Credit card updated successfully.');
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
            'product' => $subscriptionName
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function invoices()
    {
        $user = Auth::user();

        $invoices = $user->invoices();

        return view('webapp.account.invoices', compact('invoices'));
    }

    /**
     * @return Application|Factory|View
     */
    public function profile()
    {
        return view('webapp.account.profile');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
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
