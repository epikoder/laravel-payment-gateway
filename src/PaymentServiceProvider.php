<?php
namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Classes\BasePaymentGateway;
use Epikoder\LaravelPaymentGateway\Contracts\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends  ServiceProvider
{
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                realpath(__DIR__.'/../config/gateway.php') => config_path('gateway.php'),
            ]);
        }
    }

    public function register()
    {
        $this->app->bind(PaymentGatewayInterface::class, BasePaymentGateway::class);
    }
}
