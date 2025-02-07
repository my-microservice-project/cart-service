<?php

namespace App\Actions;

use App\Repositories\Contracts\CartRepositoryInterface;

class UpdateCartItemAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $productId, int $quantity): void
    {
        $this->cartRepository->updateItem($productId, $quantity);
    }
}
