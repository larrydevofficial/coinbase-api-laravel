<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    protected $table = 'crypto_currencies';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'float',
            'alias_to' => 'json',
            'prev_price' => 'float',
            'fcm_trading_session_details' => 'json',
            'price_percentage_change_24h' => 'double',
            'price_percentage_change_24h' => 'double',
            'approximate_quote_24h_volume' => 'double',
            'prev_price_percentage_change_24h' => 'double',
        ];
    }
}
