<?php

namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Contracts\PaymentGatewayInterface;
use Illuminate\Contracts\Foundation\Application;

class GatewayManager
{
    /**
     * @var PaymentGatewayInterface
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $providers = [];

    /** @var array */
    protected $allProviders = [];

    /** @var array  */
    protected $disabled;

    /**
     * Register the payment providers and get available options
     */
    public function __construct(Application $application)
    {
        /** @var PaymentGatewayInterface $gateway */
        $this->gateway = $application->get(PaymentGatewayInterface::class);

        $this->disabled = config("gateway.disabled");
        foreach (config('gateway.providers') as $key => $class) {

            /** @var PaymentProvider $provider */
            $provider = new $class;
            $this->gateway->registerProvider($provider);
            if (!in_array($provider->identifier(), $this->disabled)) {
                $this->providers[$provider->identifier()] = $provider->name();
            }
            $this->allProviders[$provider->identifier()] = $provider->name();
        }
        //SetupPaymentGateway::dispatch($this); -> if using persistent settings / save gateways to db
    }

    public function gateway(): PaymentGatewayInterface
    {
        return $this->gateway;
    }

    public function providers(): array
    {
        return $this->providers;
    }

    /**
     * Query all gateways including disabled
     */
    public function allProviders(): array
    {
        return $this->allProviders;
    }

    public function useProvider(string $string): PaymentProvider
    {
        return $this->gateway()->setActiveProvider($string);
    }
}
