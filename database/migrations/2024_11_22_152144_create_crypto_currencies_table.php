<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crypto_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique();
            $table->string('price');
            $table->string('price_percentage_change_24h');
            $table->string('volume_24h')->default(0);
            $table->string('volume_percentage_change_24h');
            $table->string('base_increment');
            $table->string('quote_increment');
            $table->string('quote_min_size');
            $table->string('quote_max_size');
            $table->string('base_min_size');
            $table->string('base_max_size');
            $table->string('base_name');
            $table->string('quote_name');
            $table->boolean('watched');
            $table->boolean('is_disabled');
            $table->boolean('new');
            $table->string('status');
            $table->boolean('cancel_only');
            $table->boolean('limit_only');
            $table->boolean('post_only');
            $table->boolean('trading_disabled');
            $table->boolean('auction_mode');
            $table->string('product_type');
            $table->string('quote_currency_id');
            $table->string('base_currency_id');
            $table->json('fcm_trading_session_details')->nullable();
            $table->string('mid_market_price');
            $table->string('alias');
            $table->json('alias_to')->nullable();
            $table->string('base_display_symbol');
            $table->string('quote_display_symbol');
            $table->boolean('view_only');
            $table->string('price_increment');
            $table->string('display_name');
            $table->string('product_venue');
            $table->string('approximate_quote_24h_volume');
            $table->string('prev_price')->default(0);
            $table->string('prev_price_percentage_change_24h')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_currencies');
    }
};
