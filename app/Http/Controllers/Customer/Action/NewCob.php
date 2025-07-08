<?php

namespace App\Http\Controllers\Customer\Action;

use App\Enum\StatusEnum;
use App\Models\CustomerDeposit;
use App\Utils\Generator;
use App\Utils\StringHandle;
use Illuminate\Support\Facades\Http;

class NewCob
{
    /**
     * @return array
     */
    public static function handle(string $amount): CustomerDeposit
    {
        /*$money = (float) StringHandle::onlyNumbers($amount) / 100;

        $bearer = "2|7iBr5WILGHH9rtvx79zyP3NTBG7kVkenHdFKX9mL589d646f";
        $url = "https://fnxpay.com/api/v1/charge/new";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->withToken($bearer)->post($url,[
            "amount" => $money,
            "callback_url" => "",
            "external_id" => Generator::externalId()
        ]);


        return $response->json();*/
        $deposit = CustomerDeposit::query()->create([
            'transaction_id' => Generator::externalId(),
            'user_id' => auth()->id(),
            'amount' => $amount,
            'emv' => '5A0847617390012345675F24032312315F340101820258008407A0000000031010950500000000009A031231239C01009F02060000000010009F03060000000000009F0702FF009F080200029F0D05B0B0B0B0B09F0E0500000000009F0F05B0B0B0B0B09F10120110A00003220000000000000000000000FF9F1101019F1A0209869F1E0831323334353637389F2608AABBCCDDEEFF11229F2701809F3303E0F0C89F34034203009F3501229F3602001E9F3704A1B2C3D49F410400000001',
            'status' => StatusEnum::Created->value,
            'expire_at' => now()->addMinutes(30),
        ]);

        return $deposit;
    }
}
