<?php

namespace App\Actions;

use App\Services\CartService;
use Illuminate\Support\Collection;

class GetCartAction
{

    public function __construct(protected CartService $cartService)
    {}

    public function execute(): Collection
    {
        return $this->cartService->getCart();
    }
}
