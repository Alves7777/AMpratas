<?php

namespace App\Domain\Contracts;

use App\Application\DTOs\PaymentIntentData;
use App\Application\DTOs\PaymentResult;

interface PaymentGatewayInterface
{
    public function driver(): string;

    public function charge(PaymentIntentData $intent): PaymentResult;
}
