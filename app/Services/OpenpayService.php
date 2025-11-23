<?php

namespace App\Services;

use Openpay\Data\Openpay;

class OpenpayService
{
    protected $client;

    public function __construct()
    {
        // Sandbox / producción
        Openpay::setProductionMode((bool) config('openpay.production', false));

        // Aseguramos país válido (MX)
        $country = strtoupper(config('openpay.country', 'MX'));

        // IP pública del cliente (para antifraude)
        $ip = request()->ip() ?? '127.0.0.1';

        $this->client = Openpay::getInstance(
            config('openpay.merchant_id'),
            config('openpay.private_key'),
            $country,
            $ip
        );
    }

    public function charges()
    {
        return $this->client->charges;
    }

    public function customers()
    {
        return $this->client->customers;
    }
}
