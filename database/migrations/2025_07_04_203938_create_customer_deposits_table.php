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
        Schema::create('customer_deposits', static function (Blueprint $table) {
            $table->id();
            $table->text('transaction_id')->nullable()->index();
            $table->foreignIdFor(\App\Models\User::class);
            $table->integer('amount');
            $table->text('emv');
            $table->text('e2e')->nullable();
            $table->string('status')->default(\App\Enum\StatusEnum::Created->value);
            $table->json('response_payload')->nullable();
            $table->timestamp('expire_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_deposits');
    }
};
