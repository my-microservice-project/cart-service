<?php

namespace App\Pipelines;

use App\Pipelines\Stages\{CheckStock};

final class AddToCartPipeline
{
    public static function stages(): array
    {
        return [
            CheckStock::class,
        ];
    }
}
