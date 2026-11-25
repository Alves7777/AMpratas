<?php

namespace App\Application\DTOs;

use App\Models\Product;
use Illuminate\Support\Collection;

final readonly class ProductPageData
{
    /**
     * @param  Collection<int, Product>  $related
     */
    public function __construct(
        public Product $product,
        public Collection $related,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product' => $this->product,
            'related' => $this->related,
        ];
    }
}
