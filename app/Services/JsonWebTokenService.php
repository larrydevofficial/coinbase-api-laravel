<?php

namespace App\Services;

use Firebase\JWT\JWT;

class JsonWebTokenService
{
    /**
     * Generates a JWT token for the given URI and method.
     *
     * @param string $method HTTP method (e.g. GET, POST)
     * @param string $uri URI to generate the token for
     * @return string JWT token     
     */
    public static function generate(string $method, string $uri): string
    {
        $request_path = $uri;
        $request_method = $method;
        $keyName = config('app.coinbase_key_name');
        $coinBaseApiUrl = str(config('app.coinbase_api_url'))->afterLast('/');
        $keySecret = str_replace('\\n', "\n", config('app.coinbase_key_secret'));

        $privateKeyResource = openssl_pkey_get_private($keySecret);
        $uri = $request_method . ' ' . $coinBaseApiUrl . $request_path;

        if (!$privateKeyResource) {
            throw new \Exception('Private key is not valid');
        }

        $time = time();
        $nonce = bin2hex(random_bytes(16));  // Generate a 32-character hexadecimal nonce

        $jwtPayload = [
            'sub' => $keyName,
            'iss' => 'cdp',
            'nbf' => $time,
            'exp' => $time + 120,  // Token valid for 120 seconds from now
            'uri' => $uri,
        ];

        $headers = [
            'typ' => 'JWT',
            'alg' => 'ES256',
            'kid' => $keyName,  // Key ID header for JWT
            'nonce' => $nonce  // Nonce included in headers for added security
        ];

        $jwtToken = JWT::encode($jwtPayload, $privateKeyResource, 'ES256', $keyName, $headers);

        return $jwtToken;
    }
}
