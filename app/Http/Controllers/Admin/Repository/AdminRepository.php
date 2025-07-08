<?php

namespace App\Http\Controllers\Admin\Repository;

use App\Enum\StatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Models\CustomerCoinPurchase;
use App\Models\CustomerDeposit;
use App\Models\CustomerProfile;
use App\Models\CustomerTransaction;
use App\Models\SystemConfig;
use App\Models\User;

class AdminRepository
{
    public function loadDashboard(): array
    {
        return [
            'totalDeposits' => $this->sunDeposits(),
            'operationalCost' => $this->operationCost(),
            'profit' => $this->profit(),
            'totalCustomer' => $this->totalCustomers(),
            'totalTransactionsPending' => $this->totalTransactionsPending(),
        ];
    }

    public function totalCustomers(): int
    {
        return User::query()
            ->where('type', 'customer')
            ->where('status', StatusEnum::Active->value)
            ->count();
    }

    public function totalDeposits(): int
    {
        return CustomerDeposit::query()
            ->where('status', StatusEnum::Paid->value)
            ->count();
    }

    public function sunDeposits(): int
    {
        return CustomerDeposit::query()
            ->where('status', StatusEnum::Paid->value)
            ->sum('amount');
    }

    public function operationCost()
    {
        return CustomerCoinPurchase::query()
            ->where('status', StatusEnum::Paid->value)
            ->sum('brl_cost');
    }

    public function profit(): float
    {
        return CustomerCoinPurchase::query()
            ->where('status', StatusEnum::Paid->value)
            ->sum('profit');
    }

    public function totalTransactionsPending(): int
    {
        return CustomerCoinPurchase::query()
            ->where('status', StatusEnum::Created->value)
            ->count();
    }

    public function config()
    {
        return SystemConfig::query()->first();
    }

    public function configUpdate(array $data): void
    {
        $config = SystemConfig::query()->first();

        if ($config) {
            $config->update($data);
        } else {
            SystemConfig::create($data);
        }
    }

    public function customers(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::query()->where('type', 'customer')->paginate(10);
    }

    public function customerUpdate(int $customerId, array $data): void
    {
        $user = User::query()->where('id', $customerId)
            ->where('type', 'customer')
            ->firstOrFail();

        $user->update($data);
    }

    public function transactions(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return CustomerCoinPurchase::query()
            ->with('user')
            ->paginate(10);
    }

    public function transactionsPending(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return CustomerCoinPurchase::query()
            ->with('user')
            ->where('status', StatusEnum::Created->value)
            ->paginate(8);
    }

    public function purchaseRefund(CustomerCoinPurchase $purchase): \Illuminate\Pagination\LengthAwarePaginator
    {
        $user = User::query()->where('id', $purchase->user_id)->first();

        return CustomerTransaction::query()->create([
            'user_id' => $purchase->user_id,
            'transaction_id' => $purchase->transaction_id,
            'amount' => $purchase->brl_amount,
            'direction' => 'in',
            'balance_before' => $user->balance,
            'balance_after' => $user->balance + $purchase->brl_amount,
            'transaction_type' => TransactionTypeEnum::Refund->value,
            'status' => StatusEnum::Finished->value,
        ]);
    }

    public function transactionsUpdate(array $data): void
    {
        CustomerCoinPurchase::query()
            ->with('id', $data['id'])
            ->update($data);
    }

    public function deposits(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = CustomerDeposit::query()
            ->with('user');

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('user_name')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'LIKE', '%'.request('user_name').'%');
            });
        }

        if (request()->filled('transaction_id')) {
            $query->where('transaction_id', 'LIKE', '%'.request('transaction_id').'%');
        }

        return $query->orderByDesc('id')->paginate(10)->withQueryString();
    }

    public function customerProfile(int $customerId)
    {
        return CustomerProfile::query()->where('user_id', $customerId)->first();

    }

    public function customerProfileUpdate(int $customerId, array $data): void
    {

        CustomerProfile::query()
            ->where('user_id', $customerId)
            ->update($data);

        if (isset($data['status'])) {
            User::query()
                ->where('id', $customerId)
                ->update(['status' => $data['status']]);
        }
    }
}
