<?php
namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;

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
     * Use this response as redirect.
     * @var \Illuminate\Http\Response
     */
    public $redirectResponse;

    /**
     * Redirect the user to this URL.
     * @var string
     */
    public $redirectUrl = '';

    /**
     * The order that is being processed.
     *
     */
    public $order;

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

    public function __construct(PaymentProvider $provider, $order)
    {
        $this->provider   = $provider;
        $this->order      = $order;
        $this->successful = false;
    }

    /**
     * The payment was successful.
     *
     * The payment is logged, associated with the order
     * and the order is marked as paid.
     *
     * @param array $data
     * @param       $response
     *
     * @return PaymentResult
     */
    public function success(array $data, $response): self
    {
        $this->successful = true;
        return $this;
    }

    /**
     * The payment was not successful.
     *
     * The payment is logged, associated with the order
     * and the order is marked as paid.
     *
     * @param array $data
     * @param       $response
     *
     * @return PaymentResult
     */
    public function fail(array $data, $response): self
    {
        $this->successful = false;
        return $this;
    }

    /**
     * The payment was successful.
     *
     * The payment is logged, associated with the order
     * and the order is marked as paid.
     *
     * @param array $data
     * @param       $response
     *
     * @return PaymentResult
     */
    public function pending(array $data, $response): self
    {
        return $this;
    }
}
