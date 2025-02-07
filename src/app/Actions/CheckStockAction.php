<?php

namespace App\Actions;

use App\Data\CartItemData;
use App\Repositories\Contracts\CartRepositoryInterface;

class CheckStockAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    )
    {}

    public function execute(CartItemData $productDTO): float
    {
        return $this->cartRepository->isStockAvailable($productDTO);
    }
}
