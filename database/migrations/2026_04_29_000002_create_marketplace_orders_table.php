<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marketplace_trade_id')
                ->nullable()
                ->constrained('marketplace_trades')
                ->nullOnDelete();

            $table->foreignId('marketplace_product_id')
                ->constrained('marketplace_products')
                ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('seller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);

            $table->string('status')->default('completed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_orders');
    }
};