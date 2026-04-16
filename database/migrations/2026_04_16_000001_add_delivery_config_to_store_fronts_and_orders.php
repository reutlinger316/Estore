<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_fronts', function (Blueprint $table) {
            $table->string('delivery_city')->nullable()->after('location');
            $table->decimal('inside_delivery_fee', 10, 2)->default(0)->after('delivery_city');
            $table->decimal('outside_delivery_fee', 10, 2)->default(0)->after('inside_delivery_fee');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_zone')->nullable()->after('type'); // inside / outside
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('delivery_zone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_zone', 'delivery_fee']);
        });

        Schema::table('store_fronts', function (Blueprint $table) {
            $table->dropColumn(['delivery_city', 'inside_delivery_fee', 'outside_delivery_fee']);
        });
    }
};