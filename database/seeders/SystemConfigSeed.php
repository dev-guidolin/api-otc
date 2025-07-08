<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SystemConfigSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SystemConfig::query()->create([
            'min_otc' => 50000.00,
            'min_exchange' => 5000.00,
            'otc_fee' => 0.01,
            'exchange_fee' => 0.015,
        ]);
    }
}
