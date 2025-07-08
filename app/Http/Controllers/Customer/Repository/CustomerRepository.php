<?php

namespace App\Http\Controllers\Customer\Repository;

use App\Actions\CryptoPriceAction;
use App\Enum\StatusEnum;
use App\Http\Controllers\Customer\Data\CustomerCoinPurchaseDTO;
use App\Models\CustomerCoinPurchase;
use App\Models\CustomerDeposit;
use App\Models\CustomerProfile;
use App\Models\CustomerTransaction;
use App\Models\SystemConfig;
use App\Utils\Generator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ValidatedInput;

class CustomerRepository
{
    public function loadDashboard(): array
    {
        return [
            'balance' => auth()->user()->balance,
            'transactions' => auth()->user()->transactions,
            'deposits' => $this->customerDeposits(auth()->id()),
        ];
    }

    public function customerDeposits(int $userId): \Illuminate\Pagination\LengthAwarePaginator
    {
        $deposits = CustomerDeposit::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(8);

        $deposits->getCollection()->transform(function ($deposit) {
            return $deposit->exceptColumns(['transaction_id', 'updated_at', 'deleted_at', 'response_payload']);
        });

        return $deposits;
    }

    public function customerPurchases(int $userId, ?string $market = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        $purchases = CustomerCoinPurchase::query()
            ->where('user_id', $userId)
            ->when($market, function ($query, $market) {
                $query->where('market', $market);
            })
            ->orderByDesc('created_at')
            ->paginate(8);

        $purchases->getCollection()->transform(function ($purchase) {
            return $purchase->exceptColumns(['transaction_id', 'updated_at', 'deleted_at', 'brl_cost', 'fee', 'original_price_per_unit', 'profit']);
        });

        return $purchases;
    }

    public function systemConfig()
    {
        return SystemConfig::query()->first();
    }

    public function coinPurchase(CustomerCoinPurchaseDTO $data): CustomerCoinPurchase
    {
        return DB::transaction(static function () use ($data) {
            $user = auth()->user();
            $userId = $user->id;

            $coin = $data->coin;
            $amountBRL = $data->amountRequest;

            if (! $coin || $amountBRL <= 0) {
                throw new \InvalidArgumentException('Invalid coin or amount.');
            }

            // Verifica saldo atualizado dentro da transação
            $user->refresh();

            if ($user->balance < $amountBRL) {
                throw new \RuntimeException('Saldo insuficiente.');
            }

            $cryptoPrices = CryptoPriceAction::handle();
            $config = SystemConfig::query()->firstOrFail();

            $type = ($amountBRL) > $config->min_otc ? 'otc' : 'exchange';

            $feePercent = $type === 'otc' ? $config->otc_fee : $config->exchange_fee;
            $rawPrice = data_get($cryptoPrices, "$coin.$type");

            $coinAmountRaw = ($amountBRL) / $rawPrice;

            $coinAmount = match ($coin) {
                'bitcoin' => number_format($coinAmountRaw, 8, '.', ''),
                'tether' => number_format($coinAmountRaw, 2, '.', ''),
                default => (string) $coinAmountRaw,
            };

            $customerBalanceBefore = $user->balance;
            $customerBalanceAfter = $customerBalanceBefore - $amountBRL;

            // Gera ID único
            $transId = Generator::externalId();

            $infoCoin = data_get($cryptoPrices, $coin);

            // Lança transação do cliente
            CustomerTransaction::create([
                'user_id' => $userId,
                'transaction_id' => $transId,
                'amount' => $amountBRL,
                'direction' => 'out',
                'balance_before' => $customerBalanceBefore,
                'balance_after' => $customerBalanceAfter,
                'transaction_type' => 'coin_purchase',
                'status' => StatusEnum::Finished->value,
            ]);

            return CustomerCoinPurchase::create([
                'market' => $type,
                'transaction_id' => $transId,
                'user_id' => $userId,
                'coin' => $coin,
                'brl_amount' => $amountBRL,
                'coin_amount' => $coinAmount,
                'original_price_per_unit' => (int) $infoCoin['original'],
                'fee' => $feePercent,
                'fee_price_per_unit' => (int) $infoCoin[$type],
                'wallet' => $data->wallet,
                'network' => $data->network,
                'status' => StatusEnum::Created->value,
            ]);
        });

    }

    public function customerProfile()
    {
        return CustomerProfile::query()
            ->where('user_id', auth()->id())
            ->first();
    }

    public function customerProfileStore(ValidatedInput $safe)
    {
        $user = auth()->user();

        // Pega o profile atual para comparar os arquivos antigos
        $currentProfile = $user->customerProfile;

        $data = $safe->except([
            'company_document_file',
            'company_social_contract_file',
            'owner_selfie',
        ]);

        $request = request();

        if ($request->hasFile('company_document_file')) {
            // Deleta arquivo antigo, se existir
            if ($currentProfile && $currentProfile->company_document_file) {
                Storage::disk('public')->delete($currentProfile->company_document_file);
            }
            $data['company_document_file'] = $request->file('company_document_file')->store('kyc/documents', 'public');
        }

        if ($request->hasFile('company_social_contract_file')) {
            if ($currentProfile && $currentProfile->company_social_contract_file) {
                Storage::disk('public')->delete($currentProfile->company_social_contract_file);
            }
            $data['company_social_contract_file'] = $request->file('company_social_contract_file')->store('kyc/contracts', 'public');
        }

        if ($request->hasFile('owner_selfie')) {
            if ($currentProfile && $currentProfile->owner_selfie) {
                Storage::disk('public')->delete($currentProfile->owner_selfie);
            }
            $data['owner_selfie'] = $request->file('owner_selfie')->store('kyc/selfies', 'public');
        }

        $data['status'] = 'pending';
        $data['message'] = null;
        $data['user_id'] = $user->id;

        return $user->customerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }
}
