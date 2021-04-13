<?php

namespace Epikoder\LaravelPaymentGateway\Gateways;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Currencies;
use Epikoder\LaravelPaymentGateway\PaymentResult;
use Illuminate\Support\Facades\Auth;

class Paystack extends PaymentProvider
{
    const NGN_MULTIPLIER = 100;
    const GHC_MULIPLIER = 10;

    public function name(): string
    {
        return 'Paystack';
    }

    public function identifier(): string
    {
        return 'paystack';
    }

    public function imageUrl(): string
    {
        return config("gateway.settings.paystack.image");
    }

    public function validate(): bool
    {
        return array_key_exists("email", $this->data);
    }

    public function process(PaymentResult $paymentResult): PaymentResult
    {
        $paystack = $this->initialize();

        try {
            $tranx = $paystack->transaction->initialize([
                'email' => $this->data['email'],
                'amount' => $this->order->amount() * $this->multiplier() * $this->rate(),
                'callback_url' => $this->returnUrl(),
                'meta' => json_encode($this->order->toArray()),
                'channels' => config("gateway.settings.{$this->identifier()}.channels"),
                'currency' => $this->currency(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        /** Set the provider for the current session */
        session([config("gateway.provider_callback") => self::class]);
        return $paymentResult->redirect($tranx->data->authorization_url);
    }

    public function complete(PaymentResult $paymentResult) : PaymentResult
    {
        $ref = request()->reference;
        if (! $ref) {
            return $paymentResult->fail([
                'message' => 'No reference supplied',
            ], null);
        }

        $paystack = $this->initialize();
        try {
            $tranx = $paystack->transaction->verify($ref);
        } catch (\Throwable $e) {
            throw $e;
        }
        return $paymentResult->success($this->data, new \Illuminate\Http\Response());
    }

    public function initialize(): \Yabacon\Paystack
    {
        return new \Yabacon\Paystack($this->settings()["sk_key"]);
    }

    public function multiplier() : int
    {
        switch ($this->currency()) {
            case Currencies::NAIRA: return self::NGN_MULTIPLIER;
            case Currencies::GH_CEDIS: return self::GHC_MULIPLIER;
            default: return 1;
        }
    }
}
