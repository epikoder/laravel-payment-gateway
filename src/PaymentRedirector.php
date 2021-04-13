<?php
namespace Epikoder\LaravelPaymentGateway;

use Illuminate\Http\RedirectResponse;

class PaymentRedirector
{
    public function handlePaymentResult(PaymentResult $paymentResult)
    {
        if ($paymentResult->redirect) {
            if ($paymentResult->redirectUrl) {
                return redirect()->to($paymentResult->redirectUrl);
            }

            if (! $paymentResult->redirectResponse) {
                throw new \LogicException("Redirect requires a redirectUrl or redirectResponse");
            }

            if ($paymentResult->redirectResponse instanceof RedirectResponse) {
                return $paymentResult->redirectResponse;
            }
        }

        session([config("gateway.responseUrl") => $paymentResult->redirectResponse->getContent()]);
        return redirect()->to('checkout/response');
    }

    public function handleOffSiteReturn()
    {
        $payment_id_key = config("gateway.payment_id");
        $payment_id = request()->$payment_id_key;
        if ($payment_id && cache()->get($payment_id)) {
            $class = cache()->get(config("gateway.provider_callback"));
            $provider = new $class();
            dd($provider);
        }
    }
}
