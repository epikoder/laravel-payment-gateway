<?php
namespace Epikoder\LaravelPaymentGateway\Exceptions;

class PaymentGatewayException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}
