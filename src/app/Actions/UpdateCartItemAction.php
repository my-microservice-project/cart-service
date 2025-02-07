<?php

namespace App\Actions;

use App\Exceptions\{CartItemNotFoundException,ProductStockNotEnoughException};
use App\Repositories\Contracts\CartRepositoryInterface;
use Throwable;

class UpdateCartItemAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    ) {}

    /**
     * @throws ProductStockNotEnoughException|CartItemNotFoundException|Throwable
     */
    public function execute(int $productId, int $quantity): bool
    {
        $this->cartRepository->updateItem($productId, $quantity);
        return true;
    }
}
