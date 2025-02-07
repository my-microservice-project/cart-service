<?php

namespace App\Actions;

use App\Data\CartItemData;
use App\Pipelines\AddToCartPipeline;
use App\Services\CartService;
use Illuminate\Pipeline\Pipeline;

class AddToCartAction
{
    public function __construct(protected CartService $cartService)
    {}

    public function execute(CartItemData $cartItemData): void
    {
        $processedItem = app(Pipeline::class)
            ->send($cartItemData)
            ->through(AddToCartPipeline::stages())
            ->thenReturn();

        $this->cartService->addItemToCart($processedItem);
    }
}
