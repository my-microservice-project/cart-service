<?php

namespace App\Actions;

use App\Repositories\Contracts\CartRepositoryInterface;

class ClearCartAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    )
    {}

    public function execute(): void
    {
        $this->cartRepository->clearCart();
    }
}
