<?php

use App\Models\CryptoCurrency;

it('can store cryptos', function () {
    $cryptos = coinbaseApiCall();

    foreach ($cryptos as $crypto) {
        CryptoCurrency::create($crypto);
    }

    expect(CryptoCurrency::all()->toArray())->not()->toBeEmpty();
});