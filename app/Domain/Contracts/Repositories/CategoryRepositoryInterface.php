<?php

namespace App\Domain\Contracts\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    /**
     * @return Collection<int, Category>
     */
    public function allActiveOrdered(): Collection;

    public function findActiveBySlugOrFail(string $slug): Category;
}
