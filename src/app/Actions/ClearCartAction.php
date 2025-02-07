<?php

namespace App\Actions;

use App\Services\CartService;

class ClearCartAction
{
    public function __construct(protected CartService $cartService)
    {}

    public function execute(): void
    {
        $this->cartService->clearCart();
    }
}
