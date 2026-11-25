<?php

namespace App\Infrastructure\Payment;

use App\Domain\Contracts\PaymentGatewayInterface;
use App\Domain\Enums\PaymentMethod;

final class PaymentGatewayManager
{
    public function __construct(
        private readonly ManualWhatsappPaymentGateway $manualWhatsapp,
        private readonly MercadoPagoPaymentGateway $mercadoPago,
    ) {}

    public function resolve(PaymentMethod $method): PaymentGatewayInterface
    {
        return match ($method) {
            PaymentMethod::ManualWhatsapp => $this->manualWhatsapp,
            PaymentMethod::Pix, PaymentMethod::CreditCard, PaymentMethod::DebitCard => $this->mercadoPago,
        };
    }
}
