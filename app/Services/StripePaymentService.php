<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripePaymentService
{
    public function __construct()
    {
        // Set Stripe API key
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Create a Stripe Payment Intent
     *
     * @param float $amount Amount in EGP (will be converted to piasters)
     * @param array $metadata Additional metadata to attach to the payment
     * @return PaymentIntent
     */
    public function createPaymentIntent(float $amount, array $metadata = []): PaymentIntent
    {
        // Stripe expects amounts in the smallest currency unit (piasters for EGP)
        // 1 EGP = 100 piasters
        $amountInPiasters = (int) ($amount * 100);

        return PaymentIntent::create([
            'amount' => $amountInPiasters,
            'currency' => config('stripe.currency', 'egp'),
            'metadata' => $metadata,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
    }

    /**
     * Retrieve a Payment Intent by ID
     *
     * @param string $paymentIntentId
     * @return PaymentIntent
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return PaymentIntent::retrieve($paymentIntentId);
    }

    /**
     * Construct a webhook event from the payload and signature
     * This verifies that the webhook genuinely came from Stripe
     *
     * @param string $payload The raw request body
     * @param string $signature The Stripe-Signature header
     * @return \Stripe\Event
     * @throws SignatureVerificationException
     */
    public function constructWebhookEvent(string $payload, string $signature): \Stripe\Event
    {
        $webhookSecret = config('stripe.webhook_secret');

        return Webhook::constructEvent(
            $payload,
            $signature,
            $webhookSecret
        );
    }

    /**
     * Cancel a Payment Intent
     *
     * @param string $paymentIntentId
     * @return PaymentIntent
     */
    public function cancelPaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return PaymentIntent::retrieve($paymentIntentId)->cancel();
    }
}
