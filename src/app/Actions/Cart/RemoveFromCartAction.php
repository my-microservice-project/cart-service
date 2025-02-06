<?php

namespace App\Actions\Cart;

use App\Services\CartService;

class RemoveFromCartAction
{
    public function __construct(protected CartService $cartService)
    {}

    public function execute(int $productId): void
    {
        $this->cartService->removeItemFromCart($productId);
    }
}
