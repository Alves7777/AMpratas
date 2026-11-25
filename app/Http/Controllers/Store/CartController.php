<?php

namespace App\Http\Controllers\Store;

use App\Application\Services\CartService;
use App\Domain\Exceptions\InactiveProductException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\AddToCartRequest;
use App\Http\Requests\Store\UpdateCartRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function index(): View
    {
        return view('store.cart', $this->cartService->view()->toArray());
    }

    public function add(AddToCartRequest $request, Product $product): RedirectResponse
    {
        try {
            $this->cartService->addProduct($product, $request->quantity());
        } catch (InactiveProductException) {
            abort(404);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Peça adicionada ao carrinho.');
    }

    public function update(UpdateCartRequest $request, int $productId): RedirectResponse
    {
        $this->cartService->updateQuantity($productId, $request->quantity());

        return back()->with('success', 'Carrinho atualizado.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $this->cartService->removeProduct($productId);

        return back()->with('success', 'Item removido.');
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Sacola esvaziada.');
    }
}
