# Laravel Payment Gateway

#### Extendable payment gateway to manage different gateways in your application.

This package allows you to manage different payment providers within your application with ease.
and provides base to add any payment provider on-the-fly.

### Default providers to be implemented

> Stripe

> Paypal

> Paystack

## Getting started

`composer require epikoder/laravel-payment-gateway`

To add a payment provider extend the PaymentProvider class

`Epikoder\LaravelPaymentGateway\Abstract\PaymentProvider`

Implement

`Epikoder\LaravelPaymentGateway\Contracts\OrderInterface`


To manage the providers use the `PaymentService`
## Config
#### Add a provider
```
 "providers" => [
    'paystack' => \Epikoder\LaravelPaymentGateway\Gateways\Paystack::class,
 ],
```
## Usage
#### Process an order
```
...
use Epikoder\LaravelPaymentGateway\PaymentService;
...
{
    public function pay(PaymentService $paymentService)
    {
        $res = $paymentService->init('provider', ['data needed to complete process'])
            ->process(Order); // Order must implement order interface
        
        // $res is either a redirect or a normanl response from provider
        return $res; 
    }
}
```

Disclaimer: This package is still under development do not use in production
