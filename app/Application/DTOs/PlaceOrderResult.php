<?php

namespace App\Application\DTOs;

use App\Models\Order;

final readonly class PlaceOrderResult
{
    public function __construct(
        public Order $order,
        public PaymentResult $payment,
        public string $whatsappLink,
    ) {}
}
