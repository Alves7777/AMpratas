<?php

namespace App\Application\DTOs;

final readonly class CartViewData
{
    /**
     * @param  array<string, array{product_id:int,name:string,slug:string,price:float,image:?string,quantity:int}>  $items
     */
    public function __construct(
        public array $items,
        public float $subtotal,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'subtotal' => $this->subtotal,
        ];
    }
}
