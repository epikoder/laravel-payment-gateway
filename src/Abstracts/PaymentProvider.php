<?php

namespace Epikoder\LaravelPaymentGateway\Abstracts;

use Epikoder\LaravelPaymentGateway\Abstracts\PaymentProviderResponse;
use Illuminate\Validation\ValidationException;

abstract class PaymentProvider
{

    /**
     * Order been paid for
     * @var PaymentOrder
     */
    protected $order;

    /**
     * User inputed data
     * @var array
     */
    protected $data;

    protected $settings = [];

    public function __construct(PaymentOrder $order = null, array $data = null)
    {
        $this->settings = config("gateway.settings.{$this->identifier()}");
        $this->order = $order ?: null;
        $this->data = $data ?: null;
    }

    /**
     * Name of payment provider
     */
    abstract public function name(): string;

    /**
     * Unique identifier for the provider
     */
    abstract public function identifier(): string;

    /**
     * Get provider settings
     */
    public function settings(): array
    {
        return $this->settings;
    }

    /**
     * Update provider settings
     */
    public function updateSettings(array $settings): PaymentProvider
    {
        foreach ($settings as $key => $value) {
            if (array_key_exists($key, $this->settings)) {
                config(["gateway.settings.{$this->identifier()}.{$key}" => $value]);
            }
        }

        if (config('gateway.persistent_settings') === true) {
            $Gsettings = \Illuminate\Support\Facades\DB::table(config('gateway.persistent_table'))->where("name", $this->identifier())->first();
            $Gsettings->settings = json_encode($settings);
            $Gsettings->save();
            return $this;
        }
    }

    /**
     * Validate user data
     * @return bool
     * @throws ValidationException
     */
    abstract public function validate(): bool;

    /**
     * Process payment
     */
    abstract public function process(PaymentProviderResponse $paymentResponse): PaymentProviderResponse;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setProduct(PaymentOrder $order)
    {
        $this->order = $order;
        return $this;
    }
}
