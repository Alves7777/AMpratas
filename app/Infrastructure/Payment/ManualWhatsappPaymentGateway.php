<?php

namespace App\Infrastructure\Payment;

use App\Application\DTOs\PaymentIntentData;
use App\Application\DTOs\PaymentResult;
use App\Domain\Contracts\PaymentGatewayInterface;

/**
 * Gateway atual: registra o pedido e deixa o pagamento no WhatsApp.
 * Substitua/adicione MercadoPagoPaymentGateway, PagBankPaymentGateway, etc.
 */
final class ManualWhatsappPaymentGateway implements PaymentGatewayInterface
{
    public function driver(): string
    {
        return 'manual_whatsapp';
    }

    public function charge(PaymentIntentData $intent): PaymentResult
    {
        return PaymentResult::pendingManual('Pedido criado. Finalize o pagamento com a AMpratas pelo WhatsApp.');
    }
}
