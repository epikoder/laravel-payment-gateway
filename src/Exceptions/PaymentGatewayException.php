<?php
namespace Epikoder\LaravelPaymentGateway\Exception;

class PaymentGatewayException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}
