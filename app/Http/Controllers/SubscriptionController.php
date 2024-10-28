<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Mail\CancelledSubscriptionMail;
use App\Mail\ChangedSubscriptionMail;
use App\Mail\ResumeSubscriptionMail;
use App\Mail\SubscribedMail;
use App\Mail\UpdatedCreditCardMail;
use App\Mail\UserEmailChangedMail;
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
    const PREMIUM = 'premium';

    public function cancel(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->subscription(self::PREMIUM)) {
            $subscription = $user->subscription(self::PREMIUM);
            $subscription->cancel();

            Mail::to($user->email)->queue(new CancelledSubscriptionMail($user));
        }

        return redirect()->back()->with('warning', 'Your subscription account has been cancelled.');
    }

    public function changePlan(): RedirectResponse
    {
        /** @var Plan $plans */
        $plans = Plan::all();

        /** @var User $user */
        $user = Auth::user();

        foreach ($plans as $plan) {
            if ($plan->stripe_plan == $user->subscription('premium')->stripe_plan) {
                if ($plan->id == 1) {
                    $newPlan = Plan::query()->findOrFail(2);
                    $user->subscription(self::PREMIUM)->swap($newPlan->stripe_plan);
                    break;
                } elseif ($plan->id == 2) {
                    $newPlan = Plan::query()->findOrFail(1);
                    $user->subscription(self::PREMIUM)->swap($newPlan->stripe_plan);
                    break;
                }
            }
        }

        Mail::to($user->email)->queue(new ChangedSubscriptionMail($user, $newPlan));

        return redirect()->back()->with('success', 'Your subscription plan has been updated.');
    }

    public function create(Request $request): RedirectResponse
    {
        $teacher = Auth::user()->getTeacher()->first();
        $plan = Plan::query()->findOrFail($request->get('plan_id'));
        $paymentMethod = $request->get('payment_method');

        $request->user()
            ->newSubscription($plan->name, $plan->stripe_plan)
            ->create($paymentMethod, [
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'address' => [
                    'line1' => $teacher->address,
                    'line2' => $teacher->address_2,
                    'city' => $teacher->city,
                    'state' => $teacher->state,
                    'postal_code' => $teacher->zip,
                ],
                'email' => $teacher->email,
                'phone' => $teacher->phone,
            ]);

        Mail::to($teacher->email)->queue(new SubscribedMail($teacher));

        return redirect()->back()->with('success', 'Thank you for subscribing to our service.');
    }

    public function creditCard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $intent = $user->createSetupIntent();

        return view('webapp.account.card')
            ->with('intent', $intent);
    }

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $intent = $user->createSetupIntent();

        if ($user->subscriptions()->first() !== null) {
            return view('webapp.account.subscription');
        } else {
            $plans = Plan::all();
            return view('webapp.account.index')
                ->with('plans', $plans)
                ->with('intent', $intent);
        }
    }

    public function invoices(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $invoices = $user->invoices();

        return view('webapp.account.invoices')->with('invoices', $invoices);
    }

    public function listPlanChange(): View
    {
        /** @var Plan $plans */
        $plans = Plan::all();

        /** @var User $user */
        $user = Auth::user();

        foreach ($plans as $plan) {
            if ($plan->stripe_plan == $user->subscription(self::PREMIUM)->stripe_plan) {
                switch ($plan->id) {
                    case 1:
                        $plan = Plan::query()->findOrFail(2);
                        break;
                    case 2:
                        $plan = Plan::query()->findOrFail(1);
                        break;
                    default:
                        break;
                }
            }
        }

        return view('webapp.account.change')->with('plan', $plan);
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

    public function profile(): View
    {
        return view('webapp.account.profile');
    }

    public function resume(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $subscription = $user->subscription(self::PREMIUM);
        $subscription->resume();

        Mail::to($user->email)->queue(new ResumeSubscriptionMail($user));

        return redirect()->back()->with('success', 'Your subscription account has been reinstated.');
    }

    public function subscribed(): View
    {
        return view('webapp.account.subscription');
    }

    public function updateCreditCard(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $paymentMethod = $request->get('payment_method');
        $user->updateDefaultPaymentMethod($paymentMethod);

        Mail::to($user->email)->queue(new UpdatedCreditCardMail($user));

        return redirect()->back()->with('success', 'Credit card updated successfully.');
    }

    public function updateProfile(UpdateUserProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');

        if ($request->get('email') !== $user->email) {
            $user->email = $request->get('email');
            Mail::to($user->email)->queue(new UserEmailChangedMail($user));
        }

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

            Mail::queue(new UserPasswordUpdatedMail($user));

            return redirect()->back()->with('success', 'Password changed successfully!');
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
