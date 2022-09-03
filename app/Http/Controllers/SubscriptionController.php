<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\User;
use Stripe;
use Session;
use Exception;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Plan::all()->mapWithKeys(fn($plan) => [$plan->id => "{$plan->name} - {$plan->days} Days / {$plan->price}$"]);
        $intent = auth()->user()->createSetupIntent();
        return view('dashboards.users.subscription.create', compact('plans', 'intent'));
    }

    public function orderPost(Request $request)
    {
        $request->validate([
            // don't forget this
        ]);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $plan = Plan::query()->find($request->post('plan_id'));
        $token = $request->post('stripeToken');
        $paymentMethod = $request->post('paymentMethod');
        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            if (is_null($user->stripe_id))
                $stripeCustomer = $user->createAsStripeCustomer();
            Stripe\Customer::createSource(
                $user->stripe_id,
                ['source' => $token]
            );
            $user->newSubscription($plan->name, $plan->price_id)->create($paymentMethod, [
                'email' => $user->email,
                'ends_at' => now()->addDays($plan->days),
            ]);
            return back()->with('success', 'Subscription is completed.');
        } catch (Exception $e) {
            return back()->with('success', $e->getMessage());
        }

    }
}
