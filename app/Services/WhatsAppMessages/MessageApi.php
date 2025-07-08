<?php

namespace App\Services\WhatsAppMessages;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class MessageApi
{
    private string $apikey = '36c5e138-c291-4673-94ec-77796d940bc1';

    public function __construct(public string $customerId) {}

    /**
     * @throws ConnectionException
     */
    public function sendTextMessage(string $message, string $customerType): void
    {

        $message_to_group = $customerType === 'group' ? '1' : '0';
        $check_status = $customerType === 'group' ? '0' : '1';
        $value = [
            'apikey' => $this->apikey,
            'phone_number' => '5562998294552',
            'contact_phone_number' => $this->customerId,
            'message_custom_id' => 'NOTIFICA',
            'message_type' => 'text',
            'message_body' => $message,
            'check_status' => $check_status,
            'schedule' => '',
            'message_to_group' => $message_to_group,
            'w_instancia_id' => '76758',
            'simule_typing' => '0',
        ];

        Http::asForm()->post('https://app.whatsgw.com.br/api/WhatsGw/Send', $value)->json();

    }

    public function sendEnquete(): void
    {

        Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://app.whatsgw.com.br/api/WhatsGw/Send', [
            'apikey' => $this->apikey,
            'phone_number' => '5562982223067',
            'contact_phone_number' => $this->customerId,
            'message_type' => 'poll',
            'message_body' => 'Qual comando quer executar?',
            'message_custom_id' => 'rds-20230214132554423-22127286',
            'poll' => [
                'name' => 'teste',
                'values' => ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
                'multiselect' => false,
                'selectableCount' => 1,
            ],
        ])->json();

    }
}
