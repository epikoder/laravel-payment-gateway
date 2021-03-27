<?php
namespace Epikoder\LaravelPaymentGateway\Contracts;

interface OrderInterface
{
    public function identifier() : string;
    public function amount() : int;
    public function id () : string;
}
