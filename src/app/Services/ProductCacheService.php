<?php

namespace App\Services;

use App\Enums\CacheEnum;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class ProductCacheService
{
    /**
     * Ürünün detaylarını cache’den getirir.
     * Örneğin, cache anahtarı: "product:{productId}"
     */
    public function getProductDetails(int $productId): ?array
    {
        return Cache::get(key: CacheEnum::PRODUCT->getValue().$productId);
    }

    /**
     * Ürünün birim fiyatını cache’den getirir.
     */
    public function getProductUnitPrice(int $productId): ?float
    {
        $details = $this->getProductDetails($productId);
        return $details['price'] ?? null;
    }

    /**
     * Ürün stok bilgisini cache’den getirir.
     * Cache anahtarı: "stock:{productId}"
     * @throws Throwable
     */
    public function getStock(int $productId): ?int
    {
        $stock = Cache::get(key: CacheEnum::STOCK->getValue().$productId);

        // Bu noktada cache'te ürün stok bilgisinin olmaması durumunda Client ile stock-service'e servise istek atabiliriz.
        throw_if($stock === null, new ProductNotFoundException());

        return $stock;
    }
}
