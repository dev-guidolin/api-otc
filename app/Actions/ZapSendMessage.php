<?php

namespace App\Actions;

use App\Services\WhatsAppMessages\SendZap;
use Illuminate\Http\Client\ConnectionException;

class ZapSendMessage
{
    /**
     * @throws ConnectionException
     */
    public static function execute(string $number, string $message, $customerType = 'customer'): void
    {
        (new SendZap($number, $message, $customerType))->message();
    }
}
