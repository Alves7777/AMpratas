<?php

namespace App\Application\DTOs;

use App\Domain\Enums\PaymentMethod;
use App\Models\Order;

final readonly class PaymentIntentData
{
    public function __construct(
        public Order $order,
        public PaymentMethod $method,
        public float $amount,
        public string $customerEmail,
        public string $customerName,
        public string $customerWhatsapp,
    ) {}
}
