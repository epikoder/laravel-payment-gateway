<?php

namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Contracts\OrderInterface;

class PaymentResult
{
    /**
     * If the payment was successful.
     * @var bool
     */
    public $successful = false;

    /**
     * If this payment needs a redirect.
     * @var bool
     */
    public $redirect = false;

    /**
     * @var \Illuminate\Http\Response
     */
    public $redirectResponse;

    public $paymentResponse;

    /**
     * Redirect user to this URL.
     * @var string
     */
    public $redirectUrl = '';

    /**
     * @var OrderInterface
     */
    public $order;

    public $data;

    /**
     * Error message in case of a failure.
     * @var string
     */
    public $message;

    /**
     * The used PaymentProvider for this payment.
     * @var PaymentProvider
     */
    public $provider;

    public function __construct(PaymentProvider $provider, OrderInterface $order)
    {
        $this->provider   = $provider;
        $this->order      = $order;
        $this->successful = false;
    }

    /**
     * The payment was successful.
     *
     * @param array $data
     * @param       $response
     *
     * @return PaymentResult
     */
    public function success(array $data, $paymentResponse): self
    {
        $this->successful = true;
        $this->data = $data;
        $this->paymentResponse = $paymentResponse;
        return $this;
    }

    /**
     * The payment was not successful.
     *
     * @param array $data
     * @param       $response
     * @return PaymentResult
     */
    public function fail(array $data, $paymentResponse): self
    {
        $this->successful = false;
        $this->data = $data;
        $this->paymentResponse = $paymentResponse;
        return $this;
    }

    public function redirect(string $string): self
    {
        $this->redirect = true;
        $this->redirectUrl = $string;
        return $this;
    }
}
