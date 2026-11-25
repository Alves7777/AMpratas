<?php

namespace App\Models;

use App\Domain\Enums\OrderStatus;
use App\Domain\Enums\PaymentMethod;
use App\Domain\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_whatsapp',
        'shipping_city',
        'shipping_state',
        'shipping_cep',
        'shipping_address',
        'notes',
        'subtotal',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'payment_meta',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'payment_status' => PaymentStatus::class,
            'payment_meta' => 'array',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
