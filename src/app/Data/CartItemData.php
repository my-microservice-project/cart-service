<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public ?float $unit_price = null
    ) {}
}
