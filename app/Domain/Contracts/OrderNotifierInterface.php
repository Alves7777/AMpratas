<?php

namespace App\Domain\Contracts;

use App\Models\Order;

interface OrderNotifierInterface
{
    public function placed(Order $order): void;
}
