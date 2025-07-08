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
        Schema::create('customer_transactions', static function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->string('transaction_id');
            $table->integer('amount'); // em centavos
            $table->enum('direction', ['in', 'out']);
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('transaction_type'); // Ex: deposit, withdraw, purchase
            $table->string('status')->default('pending');

            // Campos polimórficos

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_transactions');
    }
};
