<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Add defaults to the User profile
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('default_delivery_address')->nullable();
            $table->decimal('default_delivery_lat', 10, 8)->nullable();
            $table->decimal('default_delivery_lng', 11, 8)->nullable();
        });

        // 2. Add specific delivery info to the Order
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('type', ['takeaway', 'delivery'])->default('takeaway');
            $table->string('delivery_phone')->nullable();
            $table->text('delivery_address')->nullable();
            $table->decimal('delivery_lat', 10, 8)->nullable();
            $table->decimal('delivery_lng', 11, 8)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'default_delivery_address', 'default_delivery_lat', 'default_delivery_lng']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['type', 'delivery_phone', 'delivery_address', 'delivery_lat', 'delivery_lng']);
        });
    }
};