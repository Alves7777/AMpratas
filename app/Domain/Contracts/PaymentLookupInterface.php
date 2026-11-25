<?php

namespace App\Domain\Contracts;

use App\Application\DTOs\PaymentResult;

interface PaymentLookupInterface
{
    public function lookup(string $paymentId): PaymentResult;
}
