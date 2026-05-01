<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_loyalty_point_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('owner_type', ['admin', 'merchant']);
            $table->foreignId('merchant_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('points')->default(0);
            $table->timestamps();

            $table->unique(['customer_id', 'owner_type', 'merchant_id'], 'customer_owner_merchant_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_loyalty_point_wallets');
    }
};
