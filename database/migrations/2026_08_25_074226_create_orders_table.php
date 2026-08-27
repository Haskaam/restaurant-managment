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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();

        $table->foreignId('waiter_id')
            ->constrained('users')
            ->restrictOnDelete();

        $table->string('status')->default('accepted');

        $table->decimal('subtotal_net', 10, 2)->default(0);
        $table->decimal('subtotal_vat', 10, 2)->default(0);
        $table->decimal('subtotal_gross', 10, 2)->default(0);

        $table->decimal('discount_percent', 5, 2)->default(0);
        $table->string('discount_reason')->nullable();
        $table->decimal('discount_amount', 10, 2)->default(0);

        $table->decimal('total_net', 10, 2)->default(0);
        $table->decimal('total_vat', 10, 2)->default(0);
        $table->decimal('total_gross', 10, 2)->default(0);

        $table->timestamp('accepted_at')->nullable();
        $table->timestamp('preparation_started_at')->nullable();
        $table->timestamp('ready_at')->nullable();
        $table->timestamp('collected_at')->nullable();
        $table->timestamp('closed_at')->nullable();
        $table->timestamp('cancelled_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
