<?php

namespace App\Actions;

use App\Repositories\Contracts\CartRepositoryInterface;

class RemoveFromCartAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $productId): void
    {
        $this->cartRepository->removeItem($productId);
    }
}
