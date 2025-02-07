<?php

namespace App\Http\Resources;

use App\Data\CartItemData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id'  => $this->resource->product_id,
            'quantity'    => $this->resource->quantity,
            'unit_price'  => $this->resource->unit_price,
            'total_price' => $this->resource->quantity * $this->resource->unit_price,
        ];
    }
}
