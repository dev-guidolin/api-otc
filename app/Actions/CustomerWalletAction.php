<?php

namespace App\Actions;

use App\Enum\TransactionTypeEnum;
use App\Models\CustomerWallet;

class CustomerWalletAction
{
    public static function credit(int $userId, int $amount): CustomerWallet
    {
        $latest = CustomerWallet::where('user_id', $userId)->latest()->first();
        $balanceBefore = $latest?->balance_after ?? 0;
        $balanceAfter = $balanceBefore + $amount;

        return CustomerWallet::create([
            'user_id' => $userId,
            'amount' => $amount,
            'direction' => 'in',
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'type' => TransactionTypeEnum::Credit->value,
        ]);
    }

    public static function debit(int $userId, int $amount): CustomerWallet
    {
        $latest = CustomerWallet::where('user_id', $userId)->latest()->first();
        $balanceBefore = $latest?->balance_after ?? 0;
        $balanceAfter = $balanceBefore - $amount;

        if ($balanceAfter < 0) {
            throw new \RuntimeException('Saldo insuficiente para débito.');
        }

        return CustomerWallet::create([
            'user_id' => $userId,
            'amount' => $amount,
            'direction' => 'out',
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'type' => TransactionTypeEnum::Debit->value,
        ]);
    }

    public static function refund(int $userId, int $amount): CustomerWallet
    {
        $latest = CustomerWallet::where('user_id', $userId)->latest()->first();
        $balanceBefore = $latest?->balance_after ?? 0;
        $balanceAfter = $balanceBefore + $amount;

        return CustomerWallet::create([
            'user_id' => $userId,
            'amount' => $amount,
            'direction' => 'in',
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'type' => TransactionTypeEnum::Refund->value,
        ]);
    }
}
