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
    private Response $response;

    public function __construct()
    {
        $this->baseUrl = config('app.coinbase_api_url');
    }

    /**
     * Resuable method to make API calls to Coinbase.
     *
     * @param array{method: string, uri: string} $options
     * @return void
    */
    private function api(array ...$options): void
    {
        $uri = $options[0]['uri'];
        $method = $options[0]['method'];

        $url = "$this->baseUrl$uri?";
        $jwt = JsonWebTokenService::generate($method, $uri);
        $http = Http::withToken($jwt);

        $this->response = match ($method) {
            'POST' => $http->post($url, $options['data']),
            'GET' => $http->get($url, $this->query),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: $method"),
        };
    }

    /**
     * Resuable method to handle response from api calls to Coinbase API.
     * 
     * @throws \Exception
     * @return void
    */
    private function handleResponse(): void
    {
        if($this->response->failed()) {
            throw new \Exception("Coinbase API request failed with status code: {$this->response->status()}");
        }
    }

    /**
     * Fetches cryptos from coinbase.
     * 
     * @param bool $storeCryptos
     * @return void
    */
    public function fetchCryptos(bool $storeCryptos = true): void
    {
        $this->query = ['product_type' => CoinbaseProductTypesEnum::SPOT->value];

        $this->api([
            'method' => 'GET',
            'uri' => CoinbaseUriEnum::PRODUCTS_URI->value,
        ]);

        $this->handleResponse();

        if($storeCryptos) {
            $this->storeCryptos();
        }
    }

    /**
     * Stores previous crypto info if CryptoCurrency model is not empty.
     * 
     * @return void
    */
    private function storeCryptos(): void
    {
        $prevCryptos = CryptoCurrency::get(['product_id','price','price_percentage_change_24h']);
        CryptoCurrency::truncate();

        foreach ($this->response->collect('products') as $crypto) {
            $prevCrypto = $prevCryptos->firstWhere('product_id', $crypto['product_id']);

            if($prevCrypto) {
                $cryptoWithPrev = array_merge($crypto, [
                    'prev_price' => $prevCrypto->price,
                    'prev_price_percentage_change_24h' => $prevCrypto->price_percentage_change_24h,
                ]);
                CryptoCurrency::create($cryptoWithPrev);
            } else {
                CryptoCurrency::create($crypto);
            } 
        }
    }
}
