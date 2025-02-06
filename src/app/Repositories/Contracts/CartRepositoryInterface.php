<?php

namespace App\Repositories\Contracts;

use App\Data\CartItemData;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    /**
     * Belirtilen sepet anahtarı altında ürünü ekler veya mevcutsa miktarını günceller.
     */
    public function addItem(CartItemData $item): void;

    /**
     * Belirtilen sepet anahtarından verilen ürün id'sine ait ürünü kaldırır.
     */
    public function removeItem(int $productId): void;

    /**
     * Belirtilen sepet anahtarında, ürün id'si için miktarı günceller.
     */
    public function updateItem(int $productId, int $quantity): void;

    /**
     * Belirtilen sepet anahtarına ait tüm sepet öğelerini Collection olarak döner.
     */
    public function getCart(): Collection;

    /**
     * Belirtilen sepet anahtarına ait tüm öğeleri temizler.
     */
    public function clearCart(): void;

    /**
     * Belirtilen sepet anahtarına ait ürün miktarını kontrol eder.
     */
    public function isStockAvailable(CartItemData $cartItemDTO): bool;
}
