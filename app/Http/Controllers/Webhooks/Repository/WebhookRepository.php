<?php

namespace App\Http\Controllers\Webhooks\Repository;

use App\Actions\ZapSendMessage;
use App\Enum\StatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Http\Controllers\Webhooks\DTO\FnxPayDepositConfirmationDTO;
use App\Models\CustomerDeposit;
use App\Models\CustomerTransaction;
use App\Services\WhatsAppMessages\ZapMessages;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;

class WebhookRepository
{
    protected string $zaoNumberAlert;

    public function __construct()
    {
        $this->zaoNumberAlert = '5562999365692';
    }

    /**
     * @throws ConnectionException
     */
    public function fnxPayDepositConfirmation(FnxPayDepositConfirmationDTO $data): void
    {

        $cashIn = CustomerDeposit::query()
            ->where('transaction_id', $data->transId)
            ->where('status', StatusEnum::Created->value)
            ->first();

        if (! $cashIn) {
            return;
        }

        if ($cashIn->amount !== (int) $data->amount) {
            $message = ZapMessages::divergentAmount($data->transId, $cashIn->amount, $data->amount);
            ZapSendMessage::execute($this->zaoNumberAlert, $message);

            return;
        }

        try {
            DB::transaction(function () use ($cashIn, $data) {
                $cashIn->update([
                    'status' => StatusEnum::Paid->value,
                    'e2e' => $data->endToEndId,
                    'response_payload' => $data->toArray(),
                ]);

                $user = $cashIn->user()->lockForUpdate()->first();

                $balanceBefore = $user->balance;
                $balanceAfter = $balanceBefore + $cashIn->amount;

                // Lança transação financeira
                CustomerTransaction::create([
                    'transaction_id' => $data->transId,
                    'user_id' => $cashIn->user_id,
                    'amount' => $cashIn->amount,
                    'direction' => 'in',
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'transaction_type' => TransactionTypeEnum::Deposit->value,
                    'status' => StatusEnum::Finished->value,
                ]);

                // Mensagem de confirmação
                $message = ZapMessages::depositConfirmed($data->transId, $cashIn->amount);
                ZapSendMessage::execute($this->zaoNumberAlert, $message);
            });
        } catch (\Throwable $exception) {
            $message = ZapMessages::exception($exception->getMessage(), __METHOD__);
            ZapSendMessage::execute($this->zaoNumberAlert, $message);
        }
    }
}
