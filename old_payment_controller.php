<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\SubscriptionTransactions;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Trait\SubscriptionTrait;
use Modules\Subscriptions\Transformers\PlanlimitationMappingResource;
use Modules\Tax\Models\Tax;
use  Modules\Subscriptions\Transformers\SubscriptionResource;
use Modules\Subscriptions\Transformers\PlanResource;
use App\Mail\SubscriptionDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    use SubscriptionTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend::index');
    }

    public function selectPlan(Request $request)
    {
        $planId = $request->input('plan_id');
        $planName = $request->input('plan_name');
        $plan = Plan::all();

        $plans = PlanResource::collection($plan);

        $activeSubscriptions = Subscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->orderBy('id', 'desc')
            ->first();
        $currentPlanId = $activeSubscriptions ? $activeSubscriptions->plan_id : null;

        $planId = $planId ?? $currentPlanId ?? Plan::first()->id ?? null;

        $view = view('frontend::subscriptionPayment', compact('plans', 'planId', 'currentPlanId'))->render();
        return response()->json(['success' => true, 'view' => $view]);
    }

    /**
     * Handle the payment request by sending data to your payment system.
     */
    public function processPayment(Request $request)
    {
        $price = $request->input('price');
        $plan_id = $request->input('plan_id');
        $user = Auth::user();
    
        $queryParams = http_build_query([
            'user_id' => $user->id,
          	'user_name' => $user->frist_name." ".$user->last_name,
          	'user_email' => $user->email,
          	'user_phone' => $user->mobile,
            'plan_id' => $plan_id,
            'amount' => $price,
            'currency' => 'USD',
            'success_url' => env('APP_URL') . '/payment/success', //post route
            'fail_url' => env('APP_URL'). "/cancel-subscription?plan_id=$plan_id",  //post route
         //   'cancel_url' => env('APP_URL'). "/cancel-subscription?plan_id=$plan_id", //post route
           'cancel_url' => env('APP_URL'). "/payment/success", //post route
            'ipn_url' => env('APP_URL') . '/payment/ipn_url', //post route
        ]);
    
        $redirectUrl = 'https://rafusoft.com/payment?' . $queryParams;
    
       return response()->json(['redirect' => $redirectUrl]);
    }
    




    public function paymentSuccess(Request $request)
    {
        $paymentStatus = $request->input('payment_status'); 
        $paymentTransactionId = $request->input('tran_id');
        $plan_id = $request->input('plan_id');
        $user_id = $request->input('user_id');
        $amount = $request->input('amount');

        if ($paymentStatus == 'success') {
            return $this->handlePaymentSuccess($plan_id, $amount, 'custom', $paymentTransactionId);
        }

        return redirect('/')->with('error', 'Payment failed.');

        // echo $paymentStatus;

    }

    /**
     * Handle the subscription and transaction after a successful payment.
     */
    protected function handlePaymentSuccess($plan_id, $amount, $payment_type, $transaction_id)
    {
        $plan = Plan::findOrFail($plan_id);
        $limitation_data = PlanlimitationMappingResource::collection($plan->planLimitation);

        $user = Auth::user();
        $start_date = now();
        $end_date = $this->get_plan_expiration_date($start_date, $plan->duration, $plan->duration_value);
        $taxes = Tax::active()->get();
        $totalTax = 0;

        foreach ($taxes as $tax) {
            if (strtolower($tax->type) == 'fixed') {
                $totalTax += $tax->value;
            } elseif (strtolower($tax->type) == 'percentage') {
                $totalTax += ($plan->price * $tax->value) / 100;
            }
        }

        // Create the subscription
        $subscription = Subscription::create([
            'plan_id' => $plan_id,
            'user_id' => auth()->id(),
            'device_id' => 1,
            'start_date' => now(),
            'end_date' => $end_date,
            'status' => 'active',
            'amount' => $plan->price,
            'discount_percentage' => $plan->discount_percentage,
            'tax_amount' => $totalTax,
            'total_amount' => $amount,
            'name' => $plan->name,
            'identifier' => $plan->identifier,
            'type' => $plan->duration,
            'duration' => $plan->duration_value,
            'level' => $plan->level,
            'plan_type' => $limitation_data ? json_encode($limitation_data) : null,
            'payment_id' => $transaction_id,
        ]);

        // Create a subscription transaction
        SubscriptionTransactions::create([
            'user_id' => auth()->id(),
            'amount' => $amount,
            'payment_type' => $payment_type,
            'payment_status' => 'paid',
            'tax_data' => $taxes->isEmpty() ? null : json_encode($taxes),
            'transaction_id' => $transaction_id,
            'subscriptions_id' => $subscription->id,
        ]);

        $response = new SubscriptionResource($subscription);
        $this->sendNotificationOnsubscription('new_subscription', $response);

        if (isSmtpConfigured()) {
            try {
                Mail::to($user->email)->send(new SubscriptionDetail($response));
            } catch (\Exception $e) {
                Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        auth()->user()->update(['is_subscribe' => 1]);

        return redirect('/')->with('success', 'Payment completed successfully!');
    }
}
