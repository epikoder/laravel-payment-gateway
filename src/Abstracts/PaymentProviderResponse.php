<?php
namespace Epikoder\LaravelPaymentGateway\Abstracts;

abstract class PaymentProviderResponse
{
    abstract public function authorizationUrl() : string;
    abstract public function state() : string;
}
