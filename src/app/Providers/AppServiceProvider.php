<?php

namespace App\Providers;

use App\Builders\CartKeyBuilder;
use App\Repositories\CartRepository;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\ProductCacheService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartRepositoryInterface::class, function ($app) {
            return new CartRepository(
                productCacheService: $app->make(ProductCacheService::class),
                cartKey: $app->make(CartKeyBuilder::class)->build()
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
