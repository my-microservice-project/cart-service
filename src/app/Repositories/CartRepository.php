<?php

namespace App\Repositories;

use App\Data\CartItemData;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\ProductStockNotEnoughException;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\ProductCacheService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        protected ProductCacheService $productCacheService,
        protected string $cartKey
    ) {}

    /**
     * Sepete ürün ekler.
     * @throws ProductStockNotEnoughException
     */
    public function addItem(CartItemData $item): void
    {
        $cart = $this->getCart();

        $unitPrice = $this->getProductPrice($item->product_id);

        if ($existingItem = $cart->firstWhere('product_id', $item->product_id)) {
            $updatedQuantity = $existingItem->quantity + $item->quantity;
            $this->validateStockAvailability($item->product_id, $updatedQuantity);

            $cart = $cart->map(fn ($cartItem) =>
            $cartItem->product_id === $item->product_id
                ? new CartItemData($item->product_id, $updatedQuantity, $unitPrice)
                : $cartItem
            );
        } else {
            // Stok kontrolü yap
            $this->validateStockAvailability($item->product_id, $item->quantity);
            $cart->push(new CartItemData($item->product_id, $item->quantity, $unitPrice));
        }

        $this->updateCartCache($cart);
    }

    /**
     * Sepetteki ürün miktarını günceller.
     */
    public function updateItem(int $productId, int $quantity): void
    {
        $cart = $this->getCart();

        $cartItem = $cart->firstWhere('product_id', $productId);
        if (!$cartItem) {
            throw new CartItemNotFoundException();
        }

        $this->validateStockAvailability($productId, $quantity);

        // Ürün cache'ten fiyatı çek
        $unitPrice = $this->getProductPrice($productId);

        $cart = $cart->map(fn ($item) =>
        $item->product_id === $productId
            ? new CartItemData($productId, $quantity, $unitPrice)
            : $item
        );

        $this->updateCartCache($cart);
    }

    /**
     * Sepetten ürünü kaldırır.
     */
    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();

        if (!$cart->contains('product_id', $productId)) {
            throw new CartItemNotFoundException();
        }

        $cart = $cart->reject(fn ($item) => $item->product_id === $productId);
        $this->updateCartCache($cart);
    }

    /**
     * Sepetteki ürünleri döner.
     */
    public function getCart(): Collection
    {
        return collect(Cache::get($this->cartKey, []))
            ->map(fn ($data) => new CartItemData($data['product_id'], $data['quantity'], $data['unit_price'] ?? null));
    }

    /**
     * Sepeti temizler.
     */
    public function clearCart(): void
    {
        Cache::forget($this->cartKey);
    }

    /**
     * Ürünün istenen miktarının stokta olup olmadığını kontrol eder.
     * @throws ProductStockNotEnoughException
     */
    public function isStockAvailable(CartItemData $cartItemDTO): bool
    {
        $requestedQuantity = $cartItemDTO->quantity + $this->getCartItemQuantity($cartItemDTO->product_id);
        return $this->validateStockAvailability($cartItemDTO->product_id, $requestedQuantity);
    }

    /**
     * Stok yeterliliğini kontrol eder.
     */
    private function validateStockAvailability(int $productId, int $requestedQuantity): bool
    {
        $stock = $this->getProductStock($productId);

        if ($requestedQuantity > $stock) {
            throw new ProductStockNotEnoughException();
        }

        return true;
    }

    /**
     * Ürünün stok miktarını cache’den getirir.
     * @throws \Throwable
     */
    private function getProductStock(int $productId): int
    {
        return $this->productCacheService->getStock($productId);
    }

    /**
     * Ürünün fiyatını cache’den getirir.
     */
    private function getProductPrice(int $productId): float
    {
        return $this->productCacheService->getProductUnitPrice($productId);
    }

    /**
     * Sepetteki belirli bir ürünün miktarını döner.
     */
    public function getCartItemQuantity(int $productId): int
    {
        return $this->getCart()->firstWhere('product_id', $productId)?->quantity ?? 0;
    }

    /**
     * Sepeti cache'e kaydeder.
     */
    private function updateCartCache(Collection $cart): void
    {
        Cache::put($this->cartKey, $cart->toArray(), now()->addDays(7));
    }

}
