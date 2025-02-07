<?php

namespace App\Pipelines\Stages;

use App\Actions\CheckStockAction;
use App\Data\CartItemData;
use Closure;
use Throwable;

final class CheckStock
{
    public function __construct(protected CheckStockAction $action){}

    /**
     * @throws Throwable
     */
    public function handle(CartItemData $productDTO, Closure $next): CartItemData
    {
        $productStock = $this->action->execute($productDTO);

        $productDTO->additional(['stock' => $productStock]);
        return $next($productDTO);
    }

}
