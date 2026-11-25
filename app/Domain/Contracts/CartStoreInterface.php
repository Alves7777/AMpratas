<?php

namespace App\Domain\Contracts;

use App\Models\Product;

interface CartStoreInterface
{
    /**
     * @return array<string, array{product_id:int,name:string,slug:string,price:float,image:?string,quantity:int}>
     */
    public function items(): array;

    public function count(): int;

    public function subtotal(): float;

    public function add(Product $product, int $quantity): void;

    public function update(int $productId, int $quantity): void;

    public function remove(int $productId): void;

    public function clear(): void;

    public function isEmpty(): bool;
}
