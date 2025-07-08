<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Webhooks\DTO\FnxPayDepositConfirmationDTO;
use App\Http\Controllers\Webhooks\Repository\WebhookRepository;
use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    protected WebhookRepository $model;

    public function __construct()
    {
        $this->model = new WebhookRepository;
    }

    public function fnxPay(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $transId = $request->get('external_id');
            $status = $request->get('status');
            $endToEndId = $request->get('endToEndId');
            $amount = $request->get('amount');

            if (! $transId || ! $status || ! $endToEndId || ! $amount) {
                return response()->json([
                    'message' => 'Ok',
                ]);
            }

            $dto = new FnxPayDepositConfirmationDTO($transId, $status, $endToEndId, $amount);

            $this->model->fnxPayDepositConfirmation($dto);

            return response()->json([
                'message' => 'Ok',
            ]);

        } catch (\Throwable $exception) {

            return response()->json([
                'message' => 'Ok',
            ]);

        }

    }
}
