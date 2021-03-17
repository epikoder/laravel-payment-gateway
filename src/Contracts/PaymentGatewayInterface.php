<?php

namespace Epikoder\LaravelPaymentGateway\Contracts;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentOrder;
use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProviderResponse;

interface PaymentGatewayInterface
{

    /**
     * Register available providers
     */
    public function registerProvider(PaymentProvider $paymentProvider): PaymentProvider;

    /**
     * Process the payment
     */
    public function process(PaymentOrder $order): PaymentProviderResponse;

    /**
     * Get specific provider
     */
    public function providerById(string $id): PaymentProvider;

    /**
     * Get all registered providers
     */
    public function providers(): array;

    /**
     * Get current active provider
     */
    public function activeProvider(): PaymentProvider;

    /**
     * Set the provider to use
     */
    public function setActiveProvider(string $id): PaymentProvider;
}
