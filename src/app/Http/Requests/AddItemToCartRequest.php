<?php

namespace App\Http\Requests;

use App\Data\CartItemData;
use Illuminate\Foundation\Http\FormRequest;

final class AddItemToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ];
    }

    public function payload(): CartItemData
    {
        return CartItemData::from($this->validated());
    }
}
