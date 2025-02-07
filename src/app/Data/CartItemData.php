<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CartItemData",
    title: "Cart Item Data",
    description: "Represents an item in the cart",
    type: "object"
)]
class CartItemData extends Data
{
    public function __construct(
        #[OA\Property(
            property: "product_id",
            description: "ID of the product",
            type: "integer",
            example: 15
        )]
        public int $product_id,

        #[OA\Property(
            property: "quantity",
            description: "Quantity of the product in the cart",
            type: "integer",
            example: 2
        )]
        public int $quantity,

        #[OA\Property(
            property: "unit_price",
            description: "Unit price of the product (optional)",
            type: "number",
            format: "float",
            example: 49.99
        )]
        public ?float $unit_price = null
    ) {}
}
