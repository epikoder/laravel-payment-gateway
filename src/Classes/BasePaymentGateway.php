<?php

namespace Epikoder\LaravelPaymentGateway\Classes;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentOrder;
use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProviderResponse;
use Epikoder\LaravelPaymentGateway\Contracts\PaymentGatewayInterface;
use Epikoder\LaravelPaymentGateway\Exception\PaymentGatewayException;

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
            throw new PaymentGatewayException("Duplicate provider in entry: {$paymentProvider->identifier()}");
        }
        $this->providers[$paymentProvider->identifier()] = $paymentProvider;
        return $paymentProvider;
    }

    public function process(PaymentOrder $order): PaymentProviderResponse
    {
        $response  = new PaymentProviderResponse();
        return $this->provider->process($response);
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
        throw new \Exception('No provider has been configured, you must set a provider first', 301);
    }

    public function setActiveProvider(string $id): PaymentProvider
    {
        if (isset($this->providers[$id])) {
            $this->provider = $this->providers[$id];
            return $this->provider;
        }
        throw new \Exception('Selected Provider does not exist', 400);
    }
}
