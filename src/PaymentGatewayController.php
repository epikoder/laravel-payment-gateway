<?php

namespace Epikoder\LaravelPaymentGateway;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class PaymentGatewayController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var \Epikoder\LaravelPaymentGateway\PaymentService */
    protected $paymentService;
    /** @var \Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider */
    protected $provider;
    /** @var \Illuminate\Http\Response */
    protected $responseData;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->responseData = session(config("gateway.responseUrl"));

        if (strpos(request()->url(), config('gateway.returnUrl'))) {
            $this->provider = $paymentService->callbackProvider();
        }
    }
    abstract public function response();
    abstract public function callbackResponse();
}
