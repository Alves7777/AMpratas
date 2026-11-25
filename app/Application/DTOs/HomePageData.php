<?php

namespace App\Application\DTOs;

use Illuminate\Support\Collection;

final readonly class HomePageData
{
    /**
     * @param  Collection<int, \App\Models\Category>  $categories
     * @param  Collection<int, \App\Models\Product>  $featured
     * @param  Collection<int, \App\Models\Product>  $latest
     */
    public function __construct(
        public Collection $categories,
        public Collection $featured,
        public Collection $latest,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'categories' => $this->categories,
            'featured' => $this->featured,
            'latest' => $this->latest,
        ];
    }
}
