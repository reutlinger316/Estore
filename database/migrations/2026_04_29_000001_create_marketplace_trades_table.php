<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_trades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marketplace_product_id')
                ->constrained('marketplace_products')
                ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('seller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('original_price', 10, 2);
            $table->decimal('buyer_offer_price', 10, 2)->nullable();
            $table->decimal('seller_counter_price', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();

            $table->string('status')->default('pending');
            /*
                pending
                countered
                accepted
                rejected
                cancelled
                completed
            */

            $table->text('buyer_message')->nullable();
            $table->text('seller_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_trades');
    }
};