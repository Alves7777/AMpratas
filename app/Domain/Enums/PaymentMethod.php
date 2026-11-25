<?php

namespace App\Domain\Enums;

enum PaymentMethod: string
{
    case ManualWhatsapp = 'manual_whatsapp';
    case Pix = 'pix';
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';

    public function label(): string
    {
        return match ($this) {
            self::ManualWhatsapp => 'WhatsApp (confirmação manual)',
            self::Pix => 'PIX',
            self::CreditCard => 'Cartão de crédito',
            self::DebitCard => 'Cartão de débito',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ManualWhatsapp => 'Pedido registrado; pagamento combinado no WhatsApp.',
            self::Pix => 'QR Code do Mercado Pago. No teste, pague com um usuário de sandbox.',
            self::CreditCard => 'Cartão de crédito no checkout de teste do Mercado Pago.',
            self::DebitCard => 'Cartão de débito no checkout de teste do Mercado Pago.',
        };
    }

    public function isAvailable(): bool
    {
        return (bool) config('payments.gateways.'.$this->value.'.enabled', false);
    }

    /**
     * @return array<int, self>
     */
    public static function available(): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $method): bool => $method->isAvailable()
        ));
    }
}
