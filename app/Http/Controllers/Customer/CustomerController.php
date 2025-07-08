<?php

namespace App\Http\Controllers\Customer;

use App\Actions\CryptoPriceAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Action\NewCob;
use App\Http\Controllers\Customer\Data\CustomerCoinPurchaseDTO;
use App\Http\Controllers\Customer\Repository\CustomerRepository;
use App\Http\Requests\StoreCustomerProfileRequest;
use App\Utils\StringHandle;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerRepository $model;

    public function __construct()
    {
        $this->model = new CustomerRepository;
    }

    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->loadDashboard(),
        ]);
    }

    public function newCob(Request $request): \Illuminate\Http\JsonResponse
    {
        $cob = NewCob::handle($request->get('amount'));

        return response()->json([
            'message' => 'Request Successfully',
            'data' => $cob->emv,
        ]);
    }

    public function currency(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => CryptoPriceAction::handle(),
        ]);
    }

    public function balance(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => auth()->user()->balance,
        ]);
    }

    public function systemConfig(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->systemConfig(),
        ]);
    }

    public function deposits(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Request Successfully',
            'data' => $this->model->customerDeposits(auth()->id()),
        ]);
    }

    public function newCoinPurchase(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'amount' => ['required'],
            'wallet' => ['required'],
            'network' => ['required'],
            'coin' => ['required'],
        ], [
            'amount.required' => 'The amount is required',
            'wallet.required' => 'The wallet is required',
            'network.required' => 'The network is required',
            'coin.required' => 'The coin is required',
        ]);

        $amountRequest = (int) StringHandle::onlyNumbers($request->get('amount'));

        if (auth()->user()->balance < $amountRequest) {
            abort(403, 'Your balance is insufficient to complete this transaction.');
        }

        $dto = new CustomerCoinPurchaseDTO(
            $amountRequest,
            auth()->user()->balance,
            $request->get('coin'),
            $request->get('wallet'),
            $request->get('network'),
        );

        $transaction = $this->model->coinPurchase($dto);

        return response()->json([
            'message' => 'Your transaction was successful and is currently pending verification.',
            'data' => $transaction,
        ]);

    }

    public function customerPurchaseRequest(string $market): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'message' => 'Your transaction was successful and is currently pending verification.',
            'data' => $this->model->customerPurchases(auth()->id(), $market),
        ]);
    }

    public function loadProfile(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Your transaction was successful and is currently pending verification.',
            'data' => $this->model->customerProfile(),
        ]);
    }

    public function storeProfile(StoreCustomerProfileRequest $request): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'message' => 'Your transaction was successful and is currently pending verification.',
            'data' => $this->model->customerProfileStore($request->safe()),
        ]);
    }

    public function purchases(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Your transaction was successful and is currently pending verification.',
            'data' => $this->model->customerPurchases(auth()->id()),
        ]);
    }
}
