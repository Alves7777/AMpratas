<?php

namespace App\Domain\Contracts\Repositories;

use App\Application\DTOs\CatalogFilterData;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * @return Collection<int, Product>
     */
    public function featured(int $limit = 8): Collection;

    /**
     * @return Collection<int, Product>
     */
    public function latest(int $limit = 8): Collection;

    public function paginateByFilters(CatalogFilterData $filters, int $perPage = 12): LengthAwarePaginator;

    public function paginateByCategory(Category $category, int $perPage = 12): LengthAwarePaginator;

    public function findActiveBySlugOrFail(string $slug): Product;

    /**
     * @return Collection<int, Product>
     */
    public function relatedTo(Product $product, int $limit = 4): Collection;
}
