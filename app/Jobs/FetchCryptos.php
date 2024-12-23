<?php

namespace App\Jobs;

use App\Enums\CoinbaseUriEnum;
use App\Services\Coinbase\CoinbaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchCryptos implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new CoinbaseService('GET', CoinbaseUriEnum::PRODUCTS_URI->value))
            ->fetchCryptos()
            ->storeCryptos();
    }
}
