<?php

namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Contracts\PaymentGatewayInterface;

class BasePaymentGateway implements PaymentGatewayInterface
{

    /**
     * Current payment provider
     * @var PaymentProvider
     */
    protected $provider;

    /**
     * Payment Providers
     */
    protected $providers = [];

    public function registerProvider(PaymentProvider $paymentProvider): PaymentProvider
    {
        if (array_key_exists($paymentProvider->identifier(), $this->providers)) {
            throw new \LogicException("Duplicate provider in entry: {$paymentProvider->identifier()}");
        }
        $this->providers[$paymentProvider->identifier()] = $paymentProvider;
        return $paymentProvider;
    }

    public function init(string $paymentProvider, array $data)
    {
        if (!isset($this->providers[$paymentProvider]) && !in_array($paymentProvider, config("gateway.disabled"))) {
            throw new \LogicException(sprintf("The selected provider does not exist: %s", $paymentProvider));
        }
        /** @var PaymentProvider */
        $this->provider = $this->providers[$paymentProvider];
        $this->provider->setData($data);
    }

    public function process($order): PaymentResult
    {
        if (!$this->provider) throw new \LogicException("You need to set the provider, did you call init?");

        $this->provider->setOrder($order);
        $result = new PaymentResult($this->provider, $order);
        return $this->provider->process($result);
    }

    public function providerById(string $id): PaymentProvider
    {
        return $this->providers[$id];
    }

    public function providers(): array
    {
        return $this->providers;
    }

    public function activeProvider(): PaymentProvider
    {
        if ($this->provider) {
            return $this->provider;
        }
        throw new \LogicException('No provider has been configured, you must set a provider first', 301);
    }

    public function setActiveProvider(string $id)
    {
        if (isset($this->providers[$id])) {
            return $this->providers[$id];
        }
        throw new \LogicException('Selected Provider does not exist', 400);
    }
}
