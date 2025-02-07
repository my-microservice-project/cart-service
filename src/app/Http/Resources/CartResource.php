<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'items'       => CartItemResource::collection($this->collection),
            'total_items' => $this->collection->sum('quantity'),
            'total_price' => $this->collection->sum(fn($item) => $item->quantity * $item->unit_price),
        ];
    }
}
