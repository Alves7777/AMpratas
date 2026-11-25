<?php

namespace App\Application\DTOs;

use App\Domain\Enums\ProductSort;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class CatalogPageData
{
    /**
     * @param  Collection<int, Category>  $categories
     */
    public function __construct(
        public LengthAwarePaginator $products,
        public Collection $categories,
        public string $currentCategory,
        public string $search,
        public ProductSort $sort,
        public ?Category $activeCategory = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'products' => $this->products,
            'categories' => $this->categories,
            'currentCategory' => $this->currentCategory,
            'search' => $this->search,
            'sort' => $this->sort->value,
            'activeCategory' => $this->activeCategory,
        ];
    }
}
