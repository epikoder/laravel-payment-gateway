<?php

namespace Epikoder\LaravelPaymentGateway;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProvider;
use Epikoder\LaravelPaymentGateway\Contracts\OrderInterface;
use Illuminate\Http\RedirectResponse;

class PaymentRedirector
{
    public function handlePaymentResult(PaymentResult $paymentResult)
    {
        if ($paymentResult->successful) {
            if ($paymentResult->redirectResponse instanceof RedirectResponse) {
                return $paymentResult->redirectResponse;
            }
        }

        if ($paymentResult->redirect) {
            if ($paymentResult->redirectUrl) {
                return redirect()->to($paymentResult->redirectUrl);
            }

            if (!$paymentResult->redirectResponse) {
                throw new \LogicException("Redirect requires a redirectUrl or redirectResponse");
            }

            if ($paymentResult->redirectResponse instanceof RedirectResponse) {
                return $paymentResult->redirectResponse;
            }
        }

        session([config("gateway.responseUrl") => $paymentResult->redirectResponse->getContent()]);
        return redirect()->to('checkout/response');
    }

    public function handleOffSiteReturn(PaymentProvider $provider): PaymentResult
    {
        $payment_id_key = config("gateway.payment_id");
        $payment_id = request()->$payment_id_key;
        $order = $provider->getOrderFromSession();
        if (request()->return == 'cancel') {
            cache()->forget($payment_id);
            return new PaymentResult($provider, $order);
        }
        $paymentResult = $provider->complete(new PaymentResult($provider, $order));
        cache()->forget($payment_id);
        return $paymentResult;
    }
}
