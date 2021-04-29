<?php

namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Contracts\OrderInterface;
use Epikoder\LaravelPaymentGateway\Contracts\PaymentGatewayInterface;
use Epikoder\LaravelPaymentGateway\Exceptions\PaymentGatewayException;
use Illuminate\Contracts\Foundation\Application;

class PaymentService
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

    protected $paymentRedirector;

    /**
     * Register the payment providers and get available options
     */
    public function __construct(Application $application, PaymentRedirector $paymentRedirector)
    {
        /** @var PaymentGatewayInterface $gateway */
        $this->gateway = $application->get(PaymentGatewayInterface::class);

        foreach (config('gateway.providers') as $key => $class) {

            /** @var PaymentProvider $provider */
            $provider = new $class;
            $this->gateway->registerProvider($provider);
            if (!in_array($provider->identifier(), config("gateway.disabled"))) {
                $this->providers[$provider->identifier()] = ['name' => $provider->name(), 'image' => $provider->imageUrl()];
            }
            $this->allProviders[$provider->identifier()] = $provider->name();
        }

        $this->paymentRedirector = $paymentRedirector;
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

    public function init(string $provider, array $data)
    {
        $this->gateway->init($provider, $data);
        return $this;
    }

    public function process(OrderInterface $order)
    {
        try {
            $result = $this->gateway->process($order);
        } catch (\Throwable $e) {
            if (config("app.debug")) throw $e;
            $result = new PaymentResult($this->gateway->activeProvider(), $order);
            return $result->fail($this->gateway->activeProvider()->data(), $e);
        }

        return $this->paymentRedirector->handlePaymentResult($result);
    }

    public function complete(PaymentProvider $provider): PaymentResult
    {
        return $this->paymentRedirector->handleOffSiteReturn($provider);
    }

    public function callbackProvider(): PaymentProvider
    {
        $payment_id_key = config("gateway.payment_id");
        $payment_id = request()->$payment_id_key;
        $class = cache()->get(config("gateway.provider_callback"));
        if ($payment_id && cache()->get($payment_id) && $class) {
            /** @var \Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider */
            $provider = new $class();
            return $provider;
        } else {
            throw new PaymentGatewayException('Provider class not found');
        }
    }
}
