<?php

namespace App\Jobs;

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
        (new CoinbaseService())->fetchCryptos();
    }
}
