<?php

namespace App\Domain\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case AwaitingPayment = 'awaiting_payment';
    case Paid = 'paid';
    case Failed = 'failed';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::AwaitingPayment => 'Aguardando pagamento',
            self::Paid => 'Pago',
            self::Failed => 'Falhou',
            self::Refunded => 'Estornado',
        };
    }

    public static function fromMercadoPago(string $status): self
    {
        return match ($status) {
            'approved' => self::Paid,
            'refunded', 'charged_back' => self::Refunded,
            'rejected', 'cancelled', 'canceled' => self::Failed,
            default => self::AwaitingPayment,
        };
    }

    public function checkoutMessage(): string
    {
        return match ($this) {
            self::Paid => 'Pagamento aprovado.',
            self::Failed => 'Pagamento não aprovado. Você pode tentar de novo.',
            self::Refunded => 'Pagamento estornado.',
            self::AwaitingPayment => 'Pagamento pendente. Se escolheu Pix, conclua no app do banco.',
            self::Pending => 'Pagamento pendente.',
        };
    }
}
