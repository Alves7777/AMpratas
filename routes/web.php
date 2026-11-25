<?php

use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CatalogController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/loja', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/categoria/{slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/produto/{slug}', [CatalogController::class, 'show'])->name('product.show');

Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrinho/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho', [CartController::class, 'clear'])->name('cart.clear');
Route::delete('/carrinho/{productId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pedido/{order}/sucesso', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/mercadopago/retorno', [CheckoutController::class, 'mercadopagoReturn'])->name('checkout.mercadopago.return');
Route::post('/webhooks/mercadopago', [CheckoutController::class, 'mercadopagoWebhook'])->name('checkout.mercadopago.webhook');
