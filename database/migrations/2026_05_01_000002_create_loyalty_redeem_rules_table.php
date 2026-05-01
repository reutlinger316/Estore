<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_redeem_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('owner_type', ['admin', 'merchant']);
            $table->foreignId('merchant_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('points_required');
            $table->decimal('discount_percent', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['owner_type', 'merchant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_redeem_rules');
    }
};
