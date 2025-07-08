<?php

namespace App\Http\Controllers\Customer\Data;

class CustomerCoinPurchaseDTO
{
    public function __construct(
        public int $amountRequest,
        public int $customerBalance,
        public string $coin,
        public string $wallet,
        public string $network,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            amountRequest: (int) ($data['amountRequest'] ?? 0),
            customerBalance: (int) ($data['customerBalance'] ?? 0),
            coin: $data['coin'] ?? '',
            wallet: $data['wallet'] ?? '',
            network: $data['network'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'amountRequest' => $this->amountRequest,
            'customerBalance' => $this->customerBalance,
            'coin' => $this->coin,
            'wallet' => $this->wallet,
            'network' => $this->network,
        ];
    }
}
