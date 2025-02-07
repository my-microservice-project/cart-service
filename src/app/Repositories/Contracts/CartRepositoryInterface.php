<?php

namespace App\Repositories\Contracts;

use App\Data\CartItemData;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function addItem(CartItemData $item): void;

    public function removeItem(int $productId): void;

    public function updateItem(int $productId, int $quantity): void;

    public function getCart(): Collection;

    public function clearCart(): void;

    public function isStockAvailable(CartItemData $cartItemDTO): bool;
}
