<?php

namespace App\Services\Coinbase;

use App\Models\CryptoCurrency;
use App\Enums\CoinbaseUriEnum;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Services\JsonWebTokenService;
use App\Enums\CoinbaseProductTypesEnum;

class CoinbaseService
{
    public object $data;
    private array $query;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.coinbase_api_url');
    }

    /**
     * Resuable query string helper method for api.
     * 
     * @param array $query
     * @return self
    */
    public function withQuery(array $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Resuable fun to make API calls to Coinbase.
     * 
     * @param string $method
     * @param string $uri 
     * @param array $data
     * @return Response | self
    */
    private function api(string $method, string $uri, array $data = []): Response|self
    {
        $url = "$this->baseUrl$uri";
        $jwt = JsonWebTokenService::generate($method, $uri);

        $http = Http::withToken($jwt);

        $response = match ($method) {
            'POST' => $http->post($url, $data),
            'GET' => $http->get($url, $this->query),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: $method"),
        };

        return $this->handleResponse($response);
    }

    /**
     * Resuable method to handle response from api calls to Coinbase API.
     * 
     * @param Response $response
     * @return Response | self
    */
    private function handleResponse(Response $response): Response|self
    {
        if ($response->failed()) {
            throw new \RuntimeException("Coinbase API request failed with status code: " . $response->status());
        }

        return $response; 
    }

    /**
     * Fetches cryptos from coinbase. Result contains HTTP Response object.
     * 
     * @return self
    */
    public function fetchCryptos(): self
    {
        $this->data = $this->withQuery(['product_type' => CoinbaseProductTypesEnum::SPOT->value])
            ->api('GET', CoinbaseUriEnum::PRODUCTS_URI->value);

        return $this;
    }

    /**
     * Stores previous crypto info if CryptoCurrency model is not empty.
     * 
     * @return self
    */
    public function storeCryptos(): self
    {
        $prevCryptos = CryptoCurrency::get(['product_id','price','price_percentage_change_24h']);
        CryptoCurrency::truncate();

        foreach ($this->data->collect('products') as $crypto) {
            $prevCrypto = $prevCryptos->firstWhere('product_id', $crypto['product_id']);

            if($prevCrypto) {
                $dataToCreate = array_merge($crypto, [
                    'prev_price' => $prevCrypto->price,
                    'prev_price_percentage_change_24h' => $prevCrypto->price_percentage_change_24h,
                ]);
                CryptoCurrency::create($dataToCreate);
            } else {
                CryptoCurrency::create($crypto);
            } 
        }

        return $this;
    }
}
