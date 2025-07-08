<?php

namespace App\Enum;

enum StatusEnum: string
{
    case Active = 'active';
    case Created = 'created';           // Criado, mas ainda não processado
    case Paid = 'paid';                 // Pagamento realizado
    case Available = 'available';       // Disponível para uso ou saque
    case Sold = 'sold';                 // Vendido
    case Pending = 'pending';           // Aguardando algo (pagamento, aprovação)
    case Finished = 'finished';           // Aguardando algo (pagamento, aprovação)

    // Complementares:
    case Failed = 'failed';             // Falhou (pagamento, operação, etc.)
    case Cancelled = 'cancelled';       // Cancelado pelo sistema ou usuário
    case Refunded = 'refunded';         // Valor devolvido ao pagador
    case Processing = 'processing';     // Em processamento
    case Expired = 'expired';           // Expirado (prazo ou validade)
    case Approved = 'approved';         // Aprovado (pagamento, saque, cadastro, etc.)
    case Rejected = 'rejected';         // Rejeitado por algum motivo
    case OnHold = 'on_hold';            // Em espera ou retenção temporária
}
