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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')
            ->constrained('orders')
            ->cascadeOnDelete();

        $table->foreignId('dish_id')
            ->nullable()
            ->constrained('dishes')
            ->nullOnDelete();

        $table->string('dish_name');

        $table->unsignedInteger('quantity');

        $table->decimal('unit_net_price', 10, 2);
        $table->decimal('vat_rate', 5, 2);
        $table->decimal('unit_gross_price', 10, 2);

        $table->decimal('total_net', 10, 2);
        $table->decimal('total_vat', 10, 2);
        $table->decimal('total_gross', 10, 2);

        $table->string('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
