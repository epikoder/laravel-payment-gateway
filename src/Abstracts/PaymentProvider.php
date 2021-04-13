<?php

namespace Epikoder\LaravelPaymentGateway\Abstracts;

use Epikoder\LaravelPaymentGateway\Contracts\OrderInterface;
use Epikoder\LaravelPaymentGateway\PaymentResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

abstract class PaymentProvider
{
    /**
     * Order been paid for
     * @var OrderInterface|Model
     */
    protected $order;

    /**
     * User inputed data
     * @var array
     */
    protected $data;

    public function __construct(OrderInterface $order = null, array $data = null)
    {
        $this->order = $order ?: null;
        $this->data = $data ?: null;
    }

    /**
     * Name of payment provider
     */
    abstract public function name(): string;

    /**
     * Unique identifier for the provider
     */
    abstract public function identifier(): string;

    /**
     * Get provider logo url
     */
    abstract public function imageUrl(): string;

    public function currency(): string
    {
        return $this->settings()['currency'];
    }

    public function rate(): int
    {
        return config("gateway.rate");
    }


    /**
     * Get provider settings
     */
    public function settings(): array
    {
        $mode = config("gateway.settings.{$this->identifier()}.mode");
        return $mode
            ? config("gateway.settings.{$this->identifier()}.{$mode}")
            : config("gateway.settings.{$this->identifier()}");
    }

    /**
     * Validate user data
     * @return bool
     * @throws ValidationException
     */
    abstract public function validate(): bool;

    /**
     * Process payment
     */
    abstract public function process(PaymentResult $paymentResult): PaymentResult;

    /**
     * Complete payment
     */
    abstract public function complete(PaymentResult $paymentResult): PaymentResult;

    public function returnUrl()
    {
        return url('/') . '/' . config("gateway.returnUrl") . "?" . http_build_query([
            "action" => "return",
            config("gateway.payment_id") => $this->getPaymentId(),
        ]);
    }

    public function cancelUrl()
    {
        return url('/') . '/' . config("gateway.returnUrl") . "?" . http_build_query([
            "action" => "cancel",
            config("gateway.payment_id") => $this->getPaymentId(),
        ]);
    }

    public function data()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function order()
    {
        return $this->order();
    }

    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getPaymentId(): string
    {
        return session(config("gateway.payment_id"));
    }

    public function setOffSiteValue(): void
    {
        /** Set the provider for the current session */
        cache([config("gateway.provider_callback") => config("gateway.providers.{$this->identifier()}")], now()->addMinutes(30));
        cache([$this->getPaymentId() => 'Id exist'], now()->addMinutes(30));
    }
}
