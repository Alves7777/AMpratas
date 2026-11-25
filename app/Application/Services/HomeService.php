<?php

namespace App\Application\Services;

use App\Application\DTOs\HomePageData;
use App\Domain\Contracts\Repositories\CategoryRepositoryInterface;
use App\Domain\Contracts\Repositories\ProductRepositoryInterface;

final class HomeService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function buildPage(): HomePageData
    {
        return new HomePageData(
            categories: $this->categories->allActiveOrdered(),
            featured: $this->products->featured(),
            latest: $this->products->latest(),
        );
    }
}
