<?php
namespace Epikoder\LaravelPaymentGateway\Gateways;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\PaymentResult;

class Paystack extends PaymentProvider
{
    public function name(): string
    {
        return 'Paystack';
    }

    public function identifier(): string
    {
        return 'paystack';
    }

    public function logoUrl(): string
    {
        return config("gateway.settings.paystack.logo");
    }

    public function validate(): bool
    {
        return true;
    }

    public function process(PaymentResult $paymentProviderResponse): PaymentResult
    {
        $paystack = $this->initialize();

        try {
            $tranx = $paystack->transaction->initialize([]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $paymentProviderResponse;
    }

    public function initialize() : \Yabacon\Paystack
    {
        return new \Yabacon\Paystack($this->settings()["sk_key"]);
    }
}
