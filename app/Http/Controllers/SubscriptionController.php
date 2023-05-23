<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Mail\SubscribedMail;
use App\Mail\UserPasswordUpdatedMail;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    protected $receiptLimit = 12;

    /**
     * @return RedirectResponse
     */
    public function cancel(): RedirectResponse
    {
        /** @var User $user */
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
    public function changePlan(): RedirectResponse
    {
        /** @var Plan $plans */
        $plans = Plan::all();

        /** @var User $user */
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request): RedirectResponse
    {
        $teacher = Auth::user()->getTeacher()->first();

        $plan = Plan::findOrFail($request->get('plan_id'));

        $request->user()
            ->newSubscription($plan->name, $plan->stripe_plan)
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

    /**
     * @return View
     */
    public function creditCard(): View
    {
        return view('webapp.account.card');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->subscriptions()->first() !== null) {
            return view('webapp.account.subscription');
        } else {
            $plans = Plan::all();
            return view('webapp.account.index', compact('plans'));
        }
    }

    /**
     * @return View
     */
    public function invoices(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $invoices = $user->invoices();

        return view('webapp.account.invoices', compact('invoices'));
    }

    /**
     * @return View
     */
    public function listPlanChange(): View
    {
        /** @var Plan $plans */
        $plans = Plan::all();

        /** @var User $user */
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
     * @param $invoiceId
     * @return Response
     */
    public function pdfDownload($invoiceId): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $subscriptionName = ucfirst($user->subscriptions->first()->name);

        return $user->downloadInvoice($invoiceId, [
            'vendor' => 'Music Teachers Aid',
            'product' => $subscriptionName
        ]);
    }

    /**
     * @return View
     */
    public function profile(): View
    {
        return view('webapp.account.profile');
    }

    /**
     * @return RedirectResponse
     */
    public function resume(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $subscription = $user->subscription('premium');

        $subscription->resume();

        return redirect()->back()->with('success', 'Your subscription account has been reinstated.');
    }

    /**
     * @return View
     */
    public function subscribed(): View
    {
        return view('webapp.account.subscription');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateCreditCard(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $ccToken = $request->input('stripeToken');

        $user->updateCard($ccToken);

        return redirect()->back()->with('success', 'Credit card updated successfully.');
    }

    /**
     * @param UpdateUserProfileRequest $request
     * @return RedirectResponse
     */
    public function updateProfile(UpdateUserProfileRequest $request): RedirectResponse
    {

        /** @var User $user */
        $user = Auth::user();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->save();

        if ($request->get('current_password') !== null) {
            if (! (Hash::check($request->get('current_password'), Auth::user()->password))) {
                return redirect()->back()->with('error', 'Your current password does not match with the new password you provided. Please try again.');
            }

            if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
                return redirect()->back()->with('error', 'New Password cannot be same as your current password. Please choose a different password.');
            }

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user->password = bcrypt($request->get('new_password'));

            $user->save();

            Mail::to($user->email)->send(new UserPasswordUpdatedMail($user));

            return redirect()->back()->with('success', 'Password changed successfully!');
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
