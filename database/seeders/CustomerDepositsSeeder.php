<?php

namespace Database\Seeders;

use App\Models\CustomerDeposit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Random\RandomException;

class CustomerDepositsSeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        foreach (range(1, 300) as $i) {
            CustomerDeposit::query()->create([
                'transaction_id' => Str::uuid(),
                'user_id' => 2,
                'amount' => random_int(500, 10000),
                'emv' => 'emv'.Str::random(20),
                'e2e' => 'e2e'.strtoupper(Str::random(32)),
                'status' => collect(['pending', 'paid'])->random(),
                'response_payload' => [
                    'bank' => 'Banco XPTO',
                    'code' => random_int(100, 999),
                    'message' => 'Transação simulada',
                ],
                'expire_at' => Carbon::now()->addMinutes(random_int(10, 120)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
