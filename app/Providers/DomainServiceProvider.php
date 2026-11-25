<?php

namespace App\Providers;

use App\Domain\Contracts\CartStoreInterface;
use App\Domain\Contracts\OrderNotifierInterface;
use App\Domain\Contracts\PaymentGatewayInterface;
use App\Domain\Contracts\PaymentLookupInterface;
use App\Domain\Contracts\Repositories\CategoryRepositoryInterface;
use App\Domain\Contracts\Repositories\OrderRepositoryInterface;
use App\Domain\Contracts\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Mail\LaravelOrderNotifier;
use App\Infrastructure\Payment\ManualWhatsappPaymentGateway;
use App\Infrastructure\Payment\MercadoPagoPaymentGateway;
use App\Infrastructure\Persistence\EloquentCategoryRepository;
use App\Infrastructure\Persistence\EloquentOrderRepository;
use App\Infrastructure\Persistence\EloquentProductRepository;
use App\Infrastructure\Session\SessionCartStore;
use Illuminate\Support\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(CartStoreInterface::class, SessionCartStore::class);
        $this->app->bind(PaymentGatewayInterface::class, ManualWhatsappPaymentGateway::class);
        $this->app->bind(PaymentLookupInterface::class, MercadoPagoPaymentGateway::class);
        $this->app->bind(OrderNotifierInterface::class, LaravelOrderNotifier::class);
    }
}
