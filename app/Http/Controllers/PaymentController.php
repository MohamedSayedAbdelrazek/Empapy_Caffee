<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Create a Payment Intent for the checkout session
     */
    public function createIntent(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'order_number' => 'required|string',
            ]);

            $amount = $request->amount;
            $orderNumber = $request->order_number;

            // Create Payment Intent
            $paymentIntent = $this->stripeService->createPaymentIntent($amount, [
                'order_number' => $orderNumber,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            Log::error('[Stripe] Failed to create payment intent: ' . $e->getMessage());

            return response()->json([
                'error' => 'فشل في إنشاء عملية الدفع. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * Handle Stripe Webhook events
     * This endpoint is called by Stripe when payment events occur
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            // Verify the webhook signature
            $event = $this->stripeService->constructWebhookEvent($payload, $signature);

            Log::info('[Stripe Webhook] Received event: ' . $event->type);

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSuccess($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailure($event->data->object);
                    break;

                case 'payment_intent.canceled':
                    $this->handlePaymentCanceled($event->data->object);
                    break;

                default:
                    Log::info('[Stripe Webhook] Unhandled event type: ' . $event->type);
            }

            return response()->json(['status' => 'success']);
        } catch (SignatureVerificationException $e) {
            Log::error('[Stripe Webhook] Signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('[Stripe Webhook] Error processing webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess($paymentIntent)
    {
        try {
            $transactionId = $paymentIntent->id;
            $orderNumber = $paymentIntent->metadata->order_number ?? null;

            if (!$orderNumber) {
                Log::warning('[Stripe] Payment succeeded but no order_number in metadata: ' . $transactionId);
                return;
            }

            // Find the order
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::warning('[Stripe] Payment succeeded but order not found: ' . $orderNumber);
                return;
            }

            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => $transactionId,
            ]);

            Log::info("[Stripe] Order #{$orderNumber} marked as paid. Transaction: {$transactionId}");

            // Fire OrderPaid event for notifications, loyalty points, etc.
            // event(new \App\Events\OrderPaid($order));

        } catch (\Exception $e) {
            Log::error('[Stripe] Error handling payment success: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailure($paymentIntent)
    {
        try {
            $transactionId = $paymentIntent->id;
            $orderNumber = $paymentIntent->metadata->order_number ?? null;

            if (!$orderNumber) {
                return;
            }

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'transaction_id' => $transactionId,
                ]);

                Log::info("[Stripe] Order #{$orderNumber} payment failed. Transaction: {$transactionId}");
            }
        } catch (\Exception $e) {
            Log::error('[Stripe] Error handling payment failure: ' . $e->getMessage());
        }
    }

    /**
     * Handle canceled payment
     */
    protected function handlePaymentCanceled($paymentIntent)
    {
        try {
            $transactionId = $paymentIntent->id;
            $orderNumber = $paymentIntent->metadata->order_number ?? null;

            if (!$orderNumber) {
                return;
            }

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'transaction_id' => $transactionId,
                ]);

                Log::info("[Stripe] Order #{$orderNumber} payment canceled. Transaction: {$transactionId}");
            }
        } catch (\Exception $e) {
            Log::error('[Stripe] Error handling payment cancelation: ' . $e->getMessage());
        }
    }
}
