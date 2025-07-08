<?php

namespace App\Enum;

enum TransactionTypeEnum: string
{
    case Debit = 'debit';           // Saída de dinheiro (ex: compra, saque, taxa)
    case Credit = 'credit';         // Entrada de dinheiro (ex: comissão recebida)
    case Refund = 'refund';         // Estorno ou devolução de valor
    case Deposit = 'deposit';       // Depósito realizado na carteira

    // Complementares:
    case Withdrawal = 'withdrawal'; // Solicitação de saque pelo usuário
    case Bonus = 'bonus';           // Crédito promocional ou recompensa
    case Fee = 'fee';               // Cobrança de taxa (serviço, operação, etc.)
    case TransferIn = 'transfer_in';   // Transferência recebida de outro usuário
    case TransferOut = 'transfer_out'; // Transferência enviada para outro usuário
    case Adjustment = 'adjustment';    // Correção manual no saldo
}
