<?php

namespace Epikoder\LaravelPaymentGateway\Gateways;

use Epikoder\LaravelPaymentGateway\Traits\Objectify;

class PaystackResponse
{
    use Objectify;

    /**
     * Response status
     *
     * @var bool
     */
    public $status;

    /**
     * Response message
     * @var string
     */
    public $message;

    /**
     * Response data
     * @var json
     */
    public $data;

    function __construct($json)
    {
        $this->createFromJson($json);
    }

    function __toString(): string
    {
        return utf8_encode($this->data->status);
    }

    function isSuccessful(): bool
    {
        return utf8_encode($this->status) && $this->data->status == 'success';
    }

    function getMessage(): string
    {
        return utf8_encode($this->data->gateway_response);
    }

    function getCode(): int
    {
        return ($this->data->status == 'success') ? 200 : 400;
    }

    function getReference () : String
    {
        return $this->data->reference;
    }

    function getAmount () : int
    {
        return $this->data->amount;
    }
}
