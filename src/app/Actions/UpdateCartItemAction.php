<?php

namespace App\Actions;

use App\Services\CartService;

class UpdateCartItemAction
{

    public function __construct(protected CartService $cartService)
    {}

    public function execute(int $productId, int $quantity): void
    {
        $this->cartService->updateCartItem($productId, $quantity);
    }
}
