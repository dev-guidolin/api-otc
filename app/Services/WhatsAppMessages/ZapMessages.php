<?php

namespace App\Services\WhatsAppMessages;

use App\Utils\StringHandle;

class ZapMessages
{
    public static function divergentAmount(string $transId, int $originalAmount, int $paidAmount): string
    {
        $a = StringHandle::formatCurrencyBRL($originalAmount);
        $b = StringHandle::formatCurrencyBRL($paidAmount);

        return <<<EOT
        *⚠️ Valor Divergente Detectado*
        
        *ID da Transação:* `$transId`
        
        *Valor Original:* 
        > $a
        
        *Valor Pago:* 
        > $b
        
        EOT;
    }

    public static function depositConfirmed(string $transId, int $amount): string
    {
        $a = StringHandle::formatCurrencyBRL($amount);

        return <<<EOT
        ✅ *Depósito Confirmado com Sucesso!*
        
        *ID:* `$transId`
        
        *Valor:* 
        > $a
        
        EOT;
    }

    public static function exception(string $message, string $local): string
    {
        return <<<EOT
        ❌ *Erro no Sistema*
        
        *Mensagem:* 
        > $message
        
        *Local:* 
        > $local
        
        EOT;
    }
}
