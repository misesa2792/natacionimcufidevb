<?php

namespace App\Services;

use Exception;

class SecureTokenService
{
    protected string $secret;

    public function __construct()
    {
        $this->secret = config('app.key');
    }

    /**
     * Genera un token firmado a partir de un array.
     */
    public function encode(array $data): string
    {
        $json   = json_encode($data);
        $base64 = base64_encode($json);

        $signature = hash_hmac('sha256', $base64, $this->secret);

        return $base64 . '.' . $signature;
    }

    /**
     * Decodifica y valida el token.
     * Retorna array si es válido, null si es inválido.
     */
    public function decode(string $token): ?array
    {
        if (!str_contains($token, '.')) {
            return null;
        }

        [$base64, $signature] = explode('.', $token, 2);

        $calc = hash_hmac('sha256', $base64, $this->secret);

        if (!hash_equals($signature, $calc)) {
            // Token manipulado o corrupto
            return null;
        }

        $json = base64_decode($base64, true);

        if ($json === false) {
            return null;
        }

        return json_decode($json, true);
    }
}
