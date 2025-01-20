<?php

namespace App\Livewire;

use Livewire\Component;
use App\Jobs\FetchCryptos;
use Livewire\WithPagination;
use App\Models\CryptoCurrency;
use Illuminate\Support\Facades\DB;

class CryptosAllComponent extends Component
{
    use WithPagination;
    
    public $buttonText = 'Update List';  
    public $isRefreshing = false; 

    public function mount()
    {
        if (!CryptoCurrency::exists()) {
            $this->buttonText = 'Fetching Cryptos...';
            FetchCryptos::dispatch();
            $this->dispatch('startPolling');
        }
    }

    public function render()
    {
        $cryptos = CryptoCurrency::select(
            'product_id',
            'base_name',
            'price',
            'approximate_quote_24h_volume',
            'price_percentage_change_24h',
        )
        ->orderByDesc('price_percentage_change_24h')
        ->paginate(10);

        $columns = $cryptos->isNotEmpty() ? array_flip(collect($cryptos->first())->keys()->toArray()) : [];

        return view('livewire.cryptos-all-component', [
            'cryptos' => $cryptos,
            'columns' => $columns,
        ]);
    }

    public function refreshCryptos(): void
    {
        if ($this->isRefreshing) {
            return;
        }
    
        $this->isRefreshing = true;

        $this->buttonText = 'Updating...';
        FetchCryptos::dispatch();
        $this->dispatch('startPolling');
    }

    public function isJobRunning(): bool
    {
        $isJobFound = false;
        $jobs = DB::table('jobs')->pluck('payload');

        if ($jobs->isEmpty()) {
            $this->reset('buttonText'); 
            $this->isRefreshing = false;
            $this->dispatch('stopPolling');
        } else {
            foreach ($jobs as $item) {
                $job = json_decode($item);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }
                if (str($job->displayName)->contains('FetchCryptos')) {
                    $this->buttonText = 'Fresh Cryptos on Deck! Still Updating...';
                    $isJobFound = true;
                    break;
                }
            }
        }

        return $isJobFound;
    }
}
