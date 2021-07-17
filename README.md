# Laravel Payment Gateway

#### Extendible payment gateway to manage different gateways in your application.

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

### Order | Package model

Implement the interface `Epikoder\LaravelPaymentGateway\Contracts\OrderInterface`

```
class Order extends Model implements \Epikoder\LaravelPaymentGateway\Contracts\OrderInterface 
{
 // implements the required methods
}
```


## Config
### Add a provider
```
 "providers" => [
    'paystack' => \Epikoder\LaravelPaymentGateway\Gateways\Paystack::class,
 ],
```

To add or use your own custom provider see the config


Add a provider setting
```
"settings" => [
        "paystack" => [
            'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
                'currency' => Currencies::US_DOLLAR,
            'channels' => [
                'card', 'bank'
            ],
            'image' => 'https://tukuz.com/wp-content/uploads/2020/10/paystack-logo-vector.png',
        ]
    ],
```

### Disable provider from customer access
```
'disabled' => ['stripe',],
```

### Live and Test mode
Using live and test mode feature

```
"settings" => [
        "paystack" => [
            "mode" => "test",
            'live' => [
                'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
                'currency' => Currencies::US_DOLLAR,
            ],
            'test' => [
                'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
                'currency' => Currencies::NAIRA,
            ],
            'channels' => [
                'card', 'bank'
            ],
            'image' => 'https://tukuz.com/wp-content/uploads/2020/10/paystack-logo-vector.png',
        ]
    ],
```
### URLs and Routes
The values of the urls should be valid routes to your controller see [Complete an order](#complete-an-order)

```
 "returnUrl" => 'checkout/success',   // offsite return url
 "responseUrl" => "checkout/response",
```

## Usage

### Process an order
```
...
use Epikoder\LaravelPaymentGateway\PaymentService;
...
{
    public function pay(PaymentService $paymentService)
    {
        /** @var \Illuminate\Http\Response */
        $res = $paymentService->init('provider', request()->user()->toArray)
            ->process(Order); // Order must implement order interface
            
        return $res;  // $res is either a redirect or provider response
    }
}
```

## Complete an order 

### Simple method
```
class PayController extends \Epikoder\LaravelPaymentGateway\PaymentGatewayController {

  public function response() // Handles normal response
    {
     $content = $this->responseData;
     if (!$content) {
      // No data
     }
     
     return \Illuminate\Http\Response($content);
    }
    
    
 public function callbackResponse() // Handles off-site-response
    {
        $result = $this->paymentService->complete($this->provider);
        if ($this->provider->identifier() == 'paystack) {
            log_paystack_transactions($result);
        }
        
        if (!$result->successful) {
            return view('payment.error');
        }
        
        return view('payment.success');
    }
}
```

### Custom Method

```
{
  public function response() // Handles normal response
    {
     $content = session()->pull(config("gateway.responseUrl"));
     return \Illuminate\Http\Response($content);
    }
    
    public function callbackResponse() // Handles off-site-response
    {
        $result = $this->paymentService->complete($this->paymentService->callbackProvider());
        if ($result->provider->identifier() == 'paystack) {
            log_paystack_transactions($result);
        }
        
        if (!$result->successful) {
            return view('payment.error');
        }
        
        return view('payment.success');
    }
}

```
Transaction verification is handled by the PaymentProvider class.

## Contributing

Thank you for considering contributing to this package. :)


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
