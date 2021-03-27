<?php

namespace Epikoder\LaravelPaymentGateway\Contracts;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\PaymentResult;

interface PaymentGatewayInterface
{
    /**
     * Register available providers
     */
    public function registerProvider(PaymentProvider $paymentProvider): PaymentProvider;

    public function init(string $paymentProvider, array $data);

    /**
     * Process the payment
     */
    public function process(OrderInterface $order): PaymentResult;

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
    public function setActiveProvider(string $id);
}
