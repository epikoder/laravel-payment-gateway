<?php

use Epikoder\LaravelPaymentGateway\PaymentService;
use Illuminate\Support\Facades\Route;

Route::get(config("gateway.returnUrl"), function (PaymentService $paymentService) {
    return $paymentService->complete();
})->middleware("web");

Route::get(config("gateway.responseUrl"), function () {
    $data = session(config("gateway.responseUrl"));
    if (!$data) {
        response('Missing content', 404);
    }

    response($data);
});
