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
        Schema::table('crypto_currencies', function (Blueprint $table) {
            $table->addColumn('timestamp', 'new_at')->after('approximate_quote_24h_volume'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crypto_currencies', function (Blueprint $table) {
            //
        });
    }
};