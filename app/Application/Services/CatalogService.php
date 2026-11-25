<?php

namespace App\Application\Services;

use App\Application\DTOs\CatalogFilterData;
use App\Application\DTOs\CatalogPageData;
use App\Application\DTOs\ProductPageData;
use App\Domain\Contracts\Repositories\CategoryRepositoryInterface;
use App\Domain\Contracts\Repositories\ProductRepositoryInterface;
use App\Domain\Enums\ProductSort;

final class CatalogService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function list(CatalogFilterData $filters): CatalogPageData
    {
        return new CatalogPageData(
            products: $this->products->paginateByFilters($filters),
            categories: $this->categories->allActiveOrdered(),
            currentCategory: $filters->categorySlug,
            search: $filters->search,
            sort: $filters->sort,
        );
    }

    public function listByCategory(string $slug): CatalogPageData
    {
        $category = $this->categories->findActiveBySlugOrFail($slug);

        return new CatalogPageData(
            products: $this->products->paginateByCategory($category),
            categories: $this->categories->allActiveOrdered(),
            currentCategory: $category->slug,
            search: '',
            sort: ProductSort::Latest,
            activeCategory: $category,
        );
    }

    public function show(string $slug): ProductPageData
    {
        $product = $this->products->findActiveBySlugOrFail($slug);

        return new ProductPageData(
            product: $product,
            related: $this->products->relatedTo($product),
        );
    }
}
