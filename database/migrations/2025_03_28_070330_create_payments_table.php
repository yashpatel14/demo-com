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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
        $table->decimal('amount', 10, 2);
        $table->string('razorpay_payment_id')->nullable();
        $table->string('order_id')->nullable();
        $table->boolean('status')->default(0); // 0 = Pending, 1 = Success
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
