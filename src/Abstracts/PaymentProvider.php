<?php

namespace Epikoder\LaravelPaymentGateway\Abstracts;

use Epikoder\LaravelPaymentGateway\Contracts\PaymentOrder;
use Epikoder\LaravelPaymentGateway\PaymentResult;
use Illuminate\Validation\ValidationException;

abstract class PaymentProvider
{
    /**
     * Order been paid for
     * @var PaymentOrder
     */
    protected $order;

    /**
     * User inputed data
     * @var array
     */
    protected $data;

    public function __construct($order = null, array $data = null)
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
    abstract public function logoUrl() : string;

    /**
     * Get provider settings
     */
    public function settings(): array
    {
        $mode = config("gateway.settings.{$this->identifier()}.mode");
        return $mode
            ? config("gateway.settings.{$this->identifier()}.{$mode}}")
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
    abstract public function process(PaymentResult $paymentProviderResponse): PaymentResult;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
}
