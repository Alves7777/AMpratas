<?php

namespace App\Infrastructure\Session;

use App\Domain\Contracts\CartStoreInterface;
use App\Models\Product;
use Illuminate\Contracts\Session\Session;

final class SessionCartStore implements CartStoreInterface
{
    private const KEY = 'ampratas_cart';

    public function __construct(
        private readonly Session $session,
    ) {}

    public function items(): array
    {
        return $this->session->get(self::KEY, []);
    }

    public function count(): int
    {
        return (int) collect($this->items())->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) collect($this->items())
            ->sum(fn (array $item): float => $item['price'] * $item['quantity']);
    }

    public function add(Product $product, int $quantity): void
    {
        $cart = $this->items();
        $id = (string) $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
            $this->persist($cart);

            return;
        }

        $cart[$id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'image' => $product->imageUrl(),
            'quantity' => $quantity,
        ];

        $this->persist($cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->items();
        $id = (string) $productId;

        if (! isset($cart[$id])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$id]);
            $this->persist($cart);

            return;
        }

        $cart[$id]['quantity'] = $quantity;
        $this->persist($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[(string) $productId]);
        $this->persist($cart);
    }

    public function clear(): void
    {
        $this->session->forget(self::KEY);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @param  array<string, array{product_id:int,name:string,slug:string,price:float,image:?string,quantity:int}>  $cart
     */
    private function persist(array $cart): void
    {
        $this->session->put(self::KEY, $cart);
    }
}
