@props(['cryptos', 'columns'])

<div class="overflow-x-auto w-full">
    <table class="w-full">
        <thead>
            <tr>
                @if($columns)
                    <th class="text-white"><small>{{ isset($columns['product_id']) ? "Ticker" : ""  }}</small></th>
                    <th class="text-white"><small>{{ $columns['base_name'] ? "Name" : ""  }}</small></th>
                    <th class="text-white"><small>{{ $columns['price'] ? "Price" : ""  }}</small></th>
                    <th class="text-white"><small>{{ isset($columns['prev_price_percentage_change_24h']) ? "Prev Price%" : ""  }}</small></th>
                    <th class="text-white"><small>{{ $columns['price_percentage_change_24h'] ? "New Price%" : ""  }}</small></th>
                    <th class="text-white"><small>{{ $columns['approximate_quote_24h_volume'] ? "Vol" : ""  }}</small></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($cryptos as $crypto)
                <tr :key="$crypto->product_id">
                    <x-table-data-component :value="$crypto->product_id" />
                    <x-table-data-component :value="str()->limit($crypto->base_name, 7, '')" />
                    <x-table-data-component :value="Number::currency($crypto->price, 'USD')" />
                    <x-table-data-component :value="isset($crypto->prev_price_percentage_change_24h) ? Number::percentage($crypto['prev_price_percentage_change_24h'], 2) : ''" />
                    <x-table-data-component :value="Number::percentage($crypto->price_percentage_change_24h, 2)" />
                    <x-table-data-component :value="Number::currency($crypto->approximate_quote_24h_volume, 'USD')" />
                </tr>
            @empty
                <p>No cryptos found. Click Update Cryptos.</p>
            @endforelse
        </tbody>
    </table>
</div>