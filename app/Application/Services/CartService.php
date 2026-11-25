<?php

namespace App\Application\Services;

use App\Application\DTOs\CartViewData;
use App\Domain\Contracts\CartStoreInterface;
use App\Domain\Exceptions\InactiveProductException;
use App\Models\Product;

final class CartService
{
    public function __construct(
        private readonly CartStoreInterface $cart,
    ) {}

    public function view(): CartViewData
    {
        return new CartViewData(
            items: $this->cart->items(),
            subtotal: $this->cart->subtotal(),
        );
    }

    public function addProduct(Product $product, int $quantity = 1): void
    {
        if (! $product->is_active) {
            throw InactiveProductException::make();
        }

        $this->cart->add($product, max(1, $quantity));
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $this->cart->update($productId, $quantity);
    }

    public function removeProduct(int $productId): void
    {
        $this->cart->remove($productId);
    }

    public function clear(): void
    {
        $this->cart->clear();
    }

    public function count(): int
    {
        return $this->cart->count();
    }

    public function assertNotEmpty(): void
    {
        if ($this->cart->isEmpty()) {
            throw \App\Domain\Exceptions\EmptyCartException::make();
        }
    }
}
