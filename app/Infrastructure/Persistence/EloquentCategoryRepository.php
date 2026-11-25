<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Collection;

final class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function allActiveOrdered(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlugOrFail(string $slug): Category
    {
        return Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
