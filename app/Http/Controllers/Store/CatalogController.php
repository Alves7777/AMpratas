<?php

namespace App\Http\Controllers\Store;

use App\Application\Services\CatalogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CatalogFilterRequest;
use Illuminate\View\View;

final class CatalogController extends Controller
{
    public function __construct(
        private readonly CatalogService $catalogService,
    ) {}

    public function index(CatalogFilterRequest $request): View
    {
        return view(
            'store.catalog',
            $this->catalogService->list($request->toDto())->toArray()
        );
    }

    public function category(string $slug): View
    {
        return view(
            'store.catalog',
            $this->catalogService->listByCategory($slug)->toArray()
        );
    }

    public function show(string $slug): View
    {
        return view(
            'store.product',
            $this->catalogService->show($slug)->toArray()
        );
    }
}
