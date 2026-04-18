<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_fronts', function (Blueprint $table) {
            $table->boolean('allow_combos')->default(false)->after('outside_delivery_fee');
        });
    }

    public function down(): void
    {
        Schema::table('store_fronts', function (Blueprint $table) {
            $table->dropColumn('allow_combos');
        });
    }
};