<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Organization;
use App\Models\NotificationLog;
use App\Models\ActivityLog;
use Razorpay\Api\Api;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    private function razorpay(): Api
    {
        return new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );
    }

    // Plan definitions (in paise for Razorpay)
    private function plans(): array
    {
        return [
            'starter'      => ['name' => 'Starter',      'monthly' => 249900,  'annual' => 2399900,  'label' => '₹2,499/mo'],
            'professional' => ['name' => 'Professional', 'monthly' => 749900,  'annual' => 7199900,  'label' => '₹7,499/mo'],
            'business'     => ['name' => 'Business',     'monthly' => 1999900, 'annual' => 19199900, 'label' => '₹19,999/mo'],
        ];
    }

    // ─────────────────────────────────────────────────────
    // ONE-TIME PAYMENT
    // ─────────────────────────────────────────────────────

    /**
     * Create a Razorpay Order for a one-time payment
     */
    public function createOneTimeOrder(Request $request)
    {
        $request->validate([
            'plan'   => 'required|in:starter,professional,business',
            'period' => 'required|in:monthly,annual',
        ]);

        $plan   = $request->plan;
        $period = $request->period;
        $plans  = $this->plans();
        $amount = $plans[$plan][$period]; // in paise

        try {
            $api   = $this->razorpay();
            $order = $api->order->create([
                'amount'          => $amount,
                'currency'        => 'INR',
                'receipt'         => 'order_' . time(),
                'payment_capture' => 1,
                'notes'           => [
                    'plan'            => $plan,
                    'period'          => $period,
                    'organization_id' => Auth::user()->organization_id,
                ],
            ]);

            // Store pending payment
            $payment = Payment::create([
                'organization_id'   => Auth::user()->organization_id,
                'user_id'           => Auth::id(),
                'type'              => 'one_time',
                'plan'              => $plan,
                'amount'            => $amount / 100,
                'currency'          => 'INR',
                'razorpay_order_id' => $order->id,
                'status'            => 'pending',
            ]);

            return response()->json([
                'order_id'    => $order->id,
                'payment_id'  => $payment->id,
                'amount'      => $amount,
                'currency'    => 'INR',
                'key'         => config('services.razorpay.key_id'),
                'name'        => 'DevineSkyCRM',
                'description' => $plans[$plan]['name'] . ' Plan (' . ucfirst($period) . ')',
                'plan'        => $plan,
                'period'      => $period,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment initialization failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify and confirm one-time payment
     */
    public function verifyOneTime(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'plan'                => 'required|string',
            'payment_db_id'       => 'required|integer',
        ]);

        $api = $this->razorpay();

        try {
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment verification failed. Please contact support.'], 400);
        }

        // Activate the plan
        $org  = Auth::user()->organization;
        $plan = $request->plan;

        $org->update([
            'package'              => $plan,
            'ai_credits_used'      => 0, // reset on upgrade
            'subscription_ends_at' => Carbon::now()->addMonth(),
        ]);

        // Update payment record
        $payment = Payment::find($request->payment_db_id);
        if ($payment) {
            $payment->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'status'              => 'success',
                'paid_at'             => now(),
                'current_period_end'  => Carbon::now()->addMonth(),
            ]);
        }

        ActivityLog::log('upgraded', "Organization upgraded to {$plan} plan via one-time payment", 'Organization', $org->id);
        NotificationLog::send(Auth::id(), 'plan_upgrade', "🎉 Plan Upgraded to " . ucfirst($plan) . "!", "Your {$plan} plan is now active. AI credits have been reset.", route('settings.organization'));

        return response()->json(['success' => true, 'message' => 'Plan upgraded successfully!', 'plan' => $plan]);
    }

    // ─────────────────────────────────────────────────────
    // SUBSCRIPTION PAYMENT
    // ─────────────────────────────────────────────────────

    /**
     * Create a Razorpay Subscription
     * NOTE: You must first create Plan IDs in Razorpay Dashboard and map them here.
     */
    public function createSubscription(Request $request)
    {
        $request->validate([
            'plan'           => 'required|in:starter,professional,business',
            'razorpay_plan_id' => 'required|string', // plan ID from Razorpay dashboard
        ]);

        $plan = $request->plan;

        try {
            $api          = $this->razorpay();
            $subscription = $api->subscription->create([
                'plan_id'         => $request->razorpay_plan_id,
                'total_count'     => 12, // 12 billing cycles (months)
                'quantity'        => 1,
                'customer_notify' => 1,
                'notes'           => [
                    'plan'            => $plan,
                    'organization_id' => Auth::user()->organization_id,
                ],
            ]);

            // Save pending subscription
            $payment = Payment::create([
                'organization_id'         => Auth::user()->organization_id,
                'user_id'                 => Auth::id(),
                'type'                    => 'subscription',
                'plan'                    => $plan,
                'amount'                  => $this->plans()[$plan]['monthly'] / 100,
                'currency'                => 'INR',
                'razorpay_subscription_id' => $subscription->id,
                'status'                  => 'pending',
            ]);

            return response()->json([
                'subscription_id' => $subscription->id,
                'payment_db_id'   => $payment->id,
                'key'             => config('services.razorpay.key_id'),
                'name'            => 'DevineSkyCRM',
                'description'     => ucfirst($plan) . ' Monthly Subscription',
                'plan'            => $plan,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay subscription creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Subscription initialization failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify subscription activation
     */
    public function verifySubscription(Request $request)
    {
        $request->validate([
            'razorpay_payment_id'    => 'required|string',
            'razorpay_subscription_id' => 'required|string',
            'razorpay_signature'     => 'required|string',
            'plan'                   => 'required|string',
            'payment_db_id'          => 'required|integer',
        ]);

        $api = $this->razorpay();

        try {
            $attributes = [
                'razorpay_payment_id'    => $request->razorpay_payment_id,
                'razorpay_subscription_id' => $request->razorpay_subscription_id,
                'razorpay_signature'     => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Subscription verification failed.'], 400);
        }

        $org  = Auth::user()->organization;
        $plan = $request->plan;

        $org->update([
            'package'                  => $plan,
            'ai_credits_used'          => 0,
            'razorpay_subscription_id' => $request->razorpay_subscription_id,
            'subscription_ends_at'     => Carbon::now()->addMonth(),
        ]);

        Payment::find($request->payment_db_id)?->update([
            'razorpay_payment_id'    => $request->razorpay_payment_id,
            'razorpay_signature'     => $request->razorpay_signature,
            'status'                 => 'success',
            'paid_at'                => now(),
            'current_period_end'     => Carbon::now()->addMonth(),
        ]);

        ActivityLog::log('subscribed', "Organization subscribed to {$plan} plan", 'Organization', $org->id);
        NotificationLog::send(Auth::id(), 'plan_upgrade', "🎉 Subscription Active: " . ucfirst($plan) . "!", "Your recurring subscription is now active. Enjoy unlimited access!", route('settings.organization'));

        return response()->json(['success' => true, 'message' => 'Subscription activated!', 'plan' => $plan]);
    }

    // ─────────────────────────────────────────────────────
    // WEBHOOK (from Razorpay → your server)
    // ─────────────────────────────────────────────────────

    public function webhook(Request $request)
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        $payload       = $request->getContent();
        $signature     = $request->header('X-Razorpay-Signature');

        // Verify webhook signature
        try {
            $api = $this->razorpay();
            $api->utility->verifyWebhookSignature($payload, $signature, $webhookSecret);
        } catch (\Exception $e) {
            Log::error('Invalid Razorpay webhook signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->input('event');
        $data  = $request->input('payload');

        Log::info("Razorpay Webhook: {$event}", $data ?? []);

        switch ($event) {
            case 'payment.captured':
                // One-time payment captured
                $paymentId = $data['payment']['entity']['id'] ?? null;
                if ($paymentId) {
                    Payment::where('razorpay_payment_id', $paymentId)->update(['status' => 'success', 'paid_at' => now()]);
                }
                break;

            case 'subscription.activated':
                // Subscription is now active
                $subId = $data['subscription']['entity']['id'] ?? null;
                if ($subId) {
                    $org = Organization::where('razorpay_subscription_id', $subId)->first();
                    if ($org) {
                        $org->update(['subscription_ends_at' => Carbon::now()->addMonth()]);
                    }
                }
                break;

            case 'subscription.charged':
                // Recurring payment charged successfully
                $subId = $data['subscription']['entity']['id'] ?? null;
                if ($subId) {
                    $org = Organization::where('razorpay_subscription_id', $subId)->first();
                    if ($org) {
                        $org->update([
                            'ai_credits_used'      => 0, // reset monthly credits
                            'subscription_ends_at' => Carbon::now()->addMonth(),
                        ]);
                        NotificationLog::sendToOrg($org->id, 'billing', '✅ Subscription Renewed', 'Your monthly subscription has been renewed successfully.');
                    }
                    Payment::where('razorpay_subscription_id', $subId)
                        ->latest()
                        ->first()
                        ?->update(['status' => 'success', 'paid_at' => now()]);
                }
                break;

            case 'subscription.cancelled':
            case 'subscription.halted':
                $subId = $data['subscription']['entity']['id'] ?? null;
                if ($subId) {
                    $org = Organization::where('razorpay_subscription_id', $subId)->first();
                    if ($org) {
                        $org->update(['package' => 'starter']);
                        NotificationLog::sendToOrg($org->id, 'billing_warning', '⚠️ Subscription Cancelled', 'Your subscription has been cancelled. You have been moved to the Starter plan.');
                    }
                }
                break;

            case 'payment.failed':
                $subId = $data['payment']['entity']['subscription_id'] ?? null;
                if ($subId) {
                    $org = Organization::where('razorpay_subscription_id', $subId)->first();
                    if ($org) {
                        NotificationLog::sendToOrg($org->id, 'billing_warning', '❌ Payment Failed', 'Your subscription payment failed. Please update your payment method.');
                    }
                }
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────────────
    // PAYMENT HISTORY
    // ─────────────────────────────────────────────────────

    public function history()
    {
        $user = Auth::user();
        $payments = Payment::where('organization_id', $user->organization_id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('billing.history', compact('payments'));
    }

    public function downloadInvoice($id)
    {
        $user    = Auth::user();
        $payment = Payment::with(['user', 'organization'])->findOrFail($id);

        // Security: only members of the same org can download
        if ($payment->organization_id !== $user->organization_id && !$user->isSuperAdmin()) {
            abort(403, 'You do not have permission to download this invoice.');
        }

        $org = $payment->organization;

        $pdf = Pdf::loadView('billing.invoice', compact('payment', 'org'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'     => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'dpi'             => 150,
            ]);

        $filename = 'DevineSkyCRM-Invoice-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
