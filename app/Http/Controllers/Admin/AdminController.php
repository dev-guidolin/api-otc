<?php

namespace App\Http\Controllers\Admin;

use App\Enum\StatusEnum;
use App\Http\Controllers\Admin\Repository\AdminRepository;
use App\Http\Controllers\Controller;
use App\Models\CustomerCoinPurchase;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminRepository $model;

    public function __construct()
    {
        $this->model = new AdminRepository;
    }

    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->loadDashboard(),
        ]);
    }

    public function deposits(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->deposits(),
        ]);
    }

    public function customers(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->customers(),
        ]);
    }

    public function customerUpdate(User $user, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->model->customerUpdate($user->id, $request->toArray());

        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->customers(),
        ]);
    }

    public function customerProfile(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->customerProfile($id),
        ]);
    }

    public function customerProfileUpdate(int $id, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->model->customerProfileUpdate($id, $request->toArray());

        return response()->json([
            'message' => 'Request Successfully',
            'data' => null,
        ]);
    }

    public function config(): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->config(),
        ]);
    }

    public function configUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->model->configUpdate($request->array());

        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->config(),
        ]);
    }

    public function transactions(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->transactions(),
        ]);
    }

    public function transactionsPending(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->transactionsPending(),
        ]);
    }

    public function transactionUpdate(CustomerCoinPurchase $purchase, Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $request->get('status');

        $purchase->update($request->array());

        if ($status === StatusEnum::Cancelled->value) {
            $this->model->purchaseRefund($purchase);
        }

        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->transactionsPending(),
        ]);
    }
}
