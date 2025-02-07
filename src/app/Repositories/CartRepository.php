<?php

namespace App\Repositories;

use App\Data\CartItemData;
use App\Exceptions\{CartItemNotFoundException, ProductStockNotEnoughException};
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\ProductCacheService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        protected ProductCacheService $productCacheService,
        protected string $cartKey
    ) {}

    /**
     * @throws ProductStockNotEnoughException
     * @throws Throwable
     */
    public function addItem(CartItemData $item): void
    {
        $cart = $this->getCart();
        $existingItem = $cart->firstWhere('product_id', $item->product_id);
        $updatedQuantity = $existingItem ? $existingItem->quantity + $item->quantity : $item->quantity;

        $this->ensureStockAvailability($item->product_id, $updatedQuantity);

        $cart = $this->updateOrAddItem($cart, $item, $updatedQuantity);
        $this->updateCartCache($cart);
    }

    /**
     * @throws ProductStockNotEnoughException|CartItemNotFoundException
     * @throws Throwable
     */
    public function updateItem(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $existingItem = $cart->firstWhere('product_id', $productId);

        if (!$existingItem) {
            throw new CartItemNotFoundException();
        }

        $this->ensureStockAvailability($productId, $quantity);

        $cart = $cart->map(fn($item) =>
        $item->product_id === $productId
            ? new CartItemData($productId, $quantity, $this->getProductPrice($productId))
            : $item
        );

        $this->updateCartCache($cart);
    }

    /**
     * @throws CartItemNotFoundException
     */
    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();

        if (!$cart->contains('product_id', $productId)) {
            throw new CartItemNotFoundException();
        }

        $cart = $cart->reject(fn($item) => $item->product_id === $productId);
        $this->updateCartCache($cart);
    }

    public function getCart(): Collection
    {
        return collect(Cache::get($this->cartKey, []))
            ->map(fn($data) => new CartItemData($data['product_id'], $data['quantity'], $data['unit_price'] ?? null));
    }

    public function clearCart(): void
    {
        Cache::forget($this->cartKey);
    }

    /**
     * @throws ProductStockNotEnoughException
     * @throws Throwable
     */
    private function ensureStockAvailability(int $productId, int $quantity): void
    {
        $stock = $this->getProductStock($productId);

        if ($quantity > $stock) {
            throw new ProductStockNotEnoughException();
        }
    }

    /**
     * @throws Throwable
     */
    private function getProductStock(int $productId): int
    {
        return $this->productCacheService->getStock($productId);
    }

    private function getProductPrice(int $productId): float
    {
        return $this->productCacheService->getProductUnitPrice($productId);
    }

    private function updateOrAddItem(Collection $cart, CartItemData $item, int $updatedQuantity): Collection
    {
        $unitPrice = $this->getProductPrice($item->product_id);

        return $cart->map(fn($cartItem) =>
        $cartItem->product_id === $item->product_id
            ? new CartItemData($item->product_id, $updatedQuantity, $unitPrice)
            : $cartItem
        )->when(!$cart->firstWhere('product_id', $item->product_id), function ($cart) use ($item, $unitPrice) {
            return $cart->push(new CartItemData($item->product_id, $item->quantity, $unitPrice));
        });
    }

    private function updateCartCache(Collection $cart): void
    {
        Cache::put($this->cartKey, $cart->toArray(), now()->addDays(7));
    }

    /**
     * @throws Throwable
     */
    public function isStockAvailable(CartItemData $cartItemDTO): bool
    {
        $requestedQuantity = $cartItemDTO->quantity + $this->getCartItemQuantity($cartItemDTO->product_id);
        return $requestedQuantity <= $this->getProductStock($cartItemDTO->product_id);
    }

    public function getCartItemQuantity(int $productId): int
    {
        return $this->getCart()->firstWhere('product_id', $productId)?->quantity ?? 0;
    }


}
