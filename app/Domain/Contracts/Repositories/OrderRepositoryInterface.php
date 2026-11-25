<?php

namespace App\Domain\Contracts\Repositories;

use App\Application\DTOs\CheckoutCustomerData;
use App\Domain\Enums\PaymentStatus;
use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * @param  array<int, array{product_id:int,name:string,quantity:int,price:float}>  $cartItems
     */
    public function place(CheckoutCustomerData $customer, array $cartItems, float $subtotal): Order;

    public function markPayment(
        Order $order,
        PaymentStatus $status,
        ?string $transactionId = null,
        array $meta = [],
    ): Order;

    public function findWithItemsOrFail(int $orderId): Order;

    public function findByNumber(string $orderNumber): ?Order;
}
