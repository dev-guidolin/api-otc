<?php

namespace App\Services\WhatsAppMessages;

use Illuminate\Http\Client\ConnectionException;

class SendZap
{
    public function __construct(public string $customerId, public string $message, public string $customerType)
    {
        //
    }

    /**
     * @throws ConnectionException
     */
    public function message(): void
    {
        $messageApi = new MessageApi($this->customerId);
        $messageApi->sendTextMessage($this->message, $this->customerType);

    }
}
