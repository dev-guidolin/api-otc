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
        Schema::create('customer_coin_purchases', function (Blueprint $table) {

            $table->id();
            $table->text('transaction_id');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->string('market');

            $table->string('coin');
            $table->integer('brl_amount');
            $table->string('coin_amount');
            $table->integer('original_price_per_unit');
            $table->decimal('fee');
            $table->integer('fee_price_per_unit');

            $table->integer('brl_cost')->nullable();
            $table->integer('profit')->nullable();

            $table->string('wallet')->nullable();
            $table->string('network')->nullable();
            $table->string('hash')->nullable();

            $table->string('status')->default(\App\Enum\StatusEnum::Created->value);

            $table->timestamp('finished_at')->nullable();
            $table->text('obs')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_coin_purchases');
    }
};
