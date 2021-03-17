# Laravel Payment Gateway

#### Extendable payment gateway to manage different gateways in your application.

This package allows you to manage different payment providers within your application with ease.
and provides base to add any payment provider on-the-fly.

### TODO

> Stripe

> Paypal

> Paystack

## Getting started

`composer require epikoder/laravel-payment-gateway`

To add a payment provider extend the PaymentProvider class

`Epikoder\LaravelPaymentGateway\Abstract\PaymentProvider`

implement the abstract function.

example will be available soon.

To manage the providers use the `GatewayManager`
