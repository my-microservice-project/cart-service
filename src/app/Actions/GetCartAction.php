<?php

namespace App\Actions;

use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Collection;

class GetCartAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    ) {}

    public function execute(): Collection
    {
        return $this->cartRepository->getCart();
    }
}
