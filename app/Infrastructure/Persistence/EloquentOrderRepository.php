<?php

namespace App\Infrastructure\Persistence;

use App\Application\DTOs\CheckoutCustomerData;
use App\Application\Services\OrderNumberGenerator;
use App\Domain\Contracts\Repositories\OrderRepositoryInterface;
use App\Domain\Enums\OrderStatus;
use App\Domain\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

final class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly OrderNumberGenerator $orderNumbers,
    ) {}

    public function place(CheckoutCustomerData $customer, array $cartItems, float $subtotal): Order
    {
        return DB::transaction(function () use ($customer, $cartItems, $subtotal): Order {
            $order = Order::query()->create([
                ...$customer->toOrderAttributes(),
                'order_number' => $this->orderNumbers->generate(),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Pending,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order->load('items');
        });
    }

    public function markPayment(
        Order $order,
        PaymentStatus $status,
        ?string $transactionId = null,
        array $meta = [],
    ): Order {
        $attributes = [
            'payment_status' => $status,
            'payment_transaction_id' => $transactionId,
            'payment_meta' => $meta ?: null,
        ];

        if ($status === PaymentStatus::Paid && $order->status === OrderStatus::Pending) {
            $attributes['status'] = OrderStatus::Confirmed;
        }

        if ($status === PaymentStatus::Failed && $order->status === OrderStatus::Pending) {
            $attributes['status'] = OrderStatus::Cancelled;
        }

        $order->fill($attributes)->save();

        return $order->refresh();
    }

    public function findWithItemsOrFail(int $orderId): Order
    {
        return Order::query()
            ->with('items')
            ->findOrFail($orderId);
    }

    public function findByNumber(string $orderNumber): ?Order
    {
        return Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->first();
    }
}
