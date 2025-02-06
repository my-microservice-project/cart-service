<?php

namespace App\Pipelines\Stages;

use App\Data\CartItemData;
use App\Services\CartService;
use Closure;
use Throwable;

final class CheckStock
{
    public function __construct(protected CartService $cartService){}

    /**
     * @throws Throwable
     */
    public function handle(CartItemData $productDTO, Closure $next): CartItemData
    {
        $productStock = $this->cartService->isStockAvailable($productDTO);

        $productDTO->additional(['stock' => $productStock]);
        return $next($productDTO);
    }

}
