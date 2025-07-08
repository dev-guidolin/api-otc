<?php

namespace App\Http\Controllers\Webhooks\DTO;

class FnxPayDepositConfirmationDTO
{
    public function __construct(
        public string $transId,
        public string $status,
        public string $endToEndId,
        public float $amount,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            transId: $data['transId'] ?? '',
            status: $data['status'] ?? '',
            endToEndId: $data['endToEndId'] ?? '',
            amount: isset($data['amount']) ? (float) $data['amount'] : 0.0,
        );
    }

    public function toArray(): array
    {
        return [
            'transId' => $this->transId,
            'status' => $this->status,
            'endToEndId' => $this->endToEndId,
            'amount' => $this->amount,
        ];
    }
}
