<?php

namespace App\Actions;

use App\Models\CryptoPrice;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;

class CryptoPriceAction
{
    public static function handle(): array
    {
        $price = CryptoPrice::query()->first();

        if (! $price || $price->updated_at->lt(now()->subMinute())) {
            $url = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,tether&vs_currencies=brl';
            $response = Http::get($url);
            $data = $response->json();

            $bitcoin = data_get($data, 'bitcoin.brl');
            $tether = data_get($data, 'tether.brl');

            if ($price) {
                $price->update([
                    'bitcoin' => $bitcoin * 100,
                    'tether' => $tether * 100,
                ]);
            } else {
                $price = CryptoPrice::query()->create([
                    'bitcoin' => $bitcoin * 100,
                    'tether' => $tether * 100,
                ]);
            }
        }

        // Carrega config
        $config = SystemConfig::query()->first();
        $otcFee = $config->otc_fee;
        $exchangeFee = $config->exchange_fee;

        // Função única para somar taxa percentual
        $addFee = static fn ($value, $fee) => round($value * (1 + $fee), 2);

        return [
            'bitcoin' => [
                'original' => $price->bitcoin,
                'exchange' => (int) ($addFee($price->bitcoin, $exchangeFee)),
                'otc' => (int) ($addFee($price->bitcoin, $otcFee)),
            ],
            'tether' => [
                'original' => $price->tether,
                'exchange' => (int) ($addFee($price->tether, $exchangeFee)),
                'otc' => (int) ($addFee($price->tether, $otcFee)),
            ],
            'fee' => [
                'otc' => $otcFee,
                'exchange' => $exchangeFee,
            ],
        ];
    }
}
