<?php

namespace App\Infrastructure\Persistence;

use App\Application\DTOs\CatalogFilterData;
use App\Domain\Contracts\Repositories\ProductRepositoryInterface;
use App\Domain\Enums\ProductSort;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class EloquentProductRepository implements ProductRepositoryInterface
{
    public function featured(int $limit = 8): Collection
    {
        return $this->activeQuery()
            ->where('is_featured', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function latest(int $limit = 8): Collection
    {
        return $this->activeQuery()
            ->latest()
            ->take($limit)
            ->get();
    }

    public function paginateByFilters(CatalogFilterData $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->activeQuery();

        $this->applyCategoryFilter($query, $filters);
        $this->applySearchFilter($query, $filters);
        $this->applySort($query, $filters->sort);

        return $query->paginate($perPage)->withQueryString();
    }

    public function paginateByCategory(Category $category, int $perPage = 12): LengthAwarePaginator
    {
        return $this->activeQuery()
            ->where('category_id', $category->id)
            ->latest()
            ->paginate($perPage);
    }

    public function findActiveBySlugOrFail(string $slug): Product
    {
        return $this->activeQuery()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function relatedTo(Product $product, int $limit = 4): Collection
    {
        return $this->activeQuery()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take($limit)
            ->get();
    }

    private function activeQuery(): Builder
    {
        return Product::query()
            ->with('category')
            ->where('is_active', true);
    }

    private function applyCategoryFilter(Builder $query, CatalogFilterData $filters): void
    {
        if (! $filters->hasCategory()) {
            return;
        }

        $query->whereHas(
            'category',
            fn (Builder $builder) => $builder->where('slug', $filters->categorySlug)
        );
    }

    private function applySearchFilter(Builder $query, CatalogFilterData $filters): void
    {
        if (! $filters->hasSearch()) {
            return;
        }

        $term = '%'.$filters->search.'%';

        $query->where(function (Builder $builder) use ($term): void {
            $builder
                ->where('name', 'like', $term)
                ->orWhere('short_description', 'like', $term);
        });
    }

    private function applySort(Builder $query, ProductSort $sort): void
    {
        match ($sort) {
            ProductSort::PriceAsc => $query->orderBy('price'),
            ProductSort::PriceDesc => $query->orderByDesc('price'),
            ProductSort::Latest => $query->latest(),
        };
    }
}
