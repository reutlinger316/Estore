<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->enum('owner_type', ['admin', 'merchant']);
            $table->foreignId('merchant_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['earned', 'redeemed', 'refunded', 'adjusted']);
            $table->integer('points');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'owner_type', 'merchant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_point_transactions');
    }
};
