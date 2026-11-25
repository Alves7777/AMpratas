<?php

namespace App\Application\DTOs;

use App\Domain\Enums\ProductSort;

final readonly class CatalogFilterData
{
    public function __construct(
        public string $search,
        public string $categorySlug,
        public ProductSort $sort,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            search: trim((string) ($input['q'] ?? '')),
            categorySlug: trim((string) ($input['categoria'] ?? '')),
            sort: ProductSort::fromRequest($input['ordenar'] ?? null),
        );
    }

    public function hasSearch(): bool
    {
        return $this->search !== '';
    }

    public function hasCategory(): bool
    {
        return $this->categorySlug !== '';
    }
}
