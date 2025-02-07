<?php

namespace App\Actions;

use App\Data\CartItemData;
use App\Pipelines\AddToCartPipeline;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Pipeline\Pipeline;

class AddToCartAction
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    )
    {}

    public function execute(CartItemData $cartItemData): void
    {
        $processedItem = app(Pipeline::class)
            ->send($cartItemData)
            ->through(AddToCartPipeline::stages())
            ->thenReturn();

        $this->cartRepository->addItem($processedItem);
    }
}
