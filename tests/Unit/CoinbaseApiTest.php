<?php

use App\Enums\CoinbaseUriEnum;
use Illuminate\Support\Facades\Http;
use App\Services\JsonWebTokenService;

it('can return all coinbase products', function () {
    $coinbaseApiCall = coinbaseApiCall();
    expect($coinbaseApiCall)->toBeArray()
        ->and($coinbaseApiCall)->not()->toBeEmpty();
});

/**
 * Calls the Coinbase API and returns the response.
 *
 * @return array Coinbase API response
 */ 
function coinbaseApiCall()
{
    $uri = CoinbaseUriEnum::PRODUCTS_URI->value;
    $coinbaseApiUrl = config('app.coinbase_api_url');
    $jwt = JsonWebTokenService::generate('GET', $uri);

    $coinbaseApiCall = Http::withHeaders([
        'Authorization' => "Bearer $jwt",
        'Content-Type' => 'application/json'
    ])->get("$coinbaseApiUrl$uri", [
        'product_type' => 'SPOT',
    ])->json('products');

    return $coinbaseApiCall;
}