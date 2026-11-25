<?php

namespace App\Http\Controllers\Store;

use App\Application\Services\HomeService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __construct(
        private readonly HomeService $homeService,
    ) {}

    public function __invoke(): View
    {
        return view('store.home', $this->homeService->buildPage()->toArray());
    }
}
