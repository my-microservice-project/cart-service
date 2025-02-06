<?php

namespace App\Services;

use App\Data\CartItemData;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Collection;

final class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    ) {}

    public function addItemToCart(CartItemData $item): void
    {
        $this->cartRepository->addItem($item);
    }

    public function removeItemFromCart(int $productId): void
    {
        $this->cartRepository->removeItem($productId);
    }

    public function updateCartItem(int $productId, int $quantity): void
    {
        $this->cartRepository->updateItem($productId, $quantity);
    }

    public function getCart(): Collection
    {
        return $this->cartRepository->getCart();
    }

    public function clearCart(): void
    {
        $this->cartRepository->clearCart();
    }

    public function isStockAvailable(CartItemData $productDTO): float
    {
        return $this->cartRepository->isStockAvailable($productDTO);
    }

}
