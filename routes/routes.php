<?php

use Illuminate\Support\Facades\Route;

Route::get(config("gateway.returnUrl"))->middleware("web");
