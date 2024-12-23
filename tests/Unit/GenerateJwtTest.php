<?php

use Firebase\JWT\JWT;
use App\Enums\CoinbaseUriEnum;

it('can generate a jwt token', function () {
    $jwt = getJwtToken('GET', CoinbaseUriEnum::PRODUCTS_URI->value);
    expect($jwt)->toBeString();
});

/**
 * Generates a JWT token for the given URI and method.
 *
 * @param string $method HTTP method (e.g. GET, POST)
 * @param string $uri URI to generate the token for
 * @return string JWT token
 */
function getJwtToken(string $method, string $uri): string
{
    $keyName = config('app.coinbase_key_name');
    $coinBaseApiUrl = str(config('app.coinbase_api_url'))->afterLast('/');
    $keySecret = str(config('app.coinbase_key_secret'))->replace('\\n', "\n");

    $uri = $method . ' ' . $coinBaseApiUrl . $uri;
    $privateKeyResource = openssl_pkey_get_private($keySecret);

    if (!$privateKeyResource) {
        throw new Exception('Private key is not valid');
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

    return JWT::encode($jwtPayload, $privateKeyResource, 'ES256', $keyName, $headers);
}