<?php

namespace App\Enums;

enum CacheEnum: string
{
    case PRODUCT = 'product:';

    case STOCK = 'stock:';

    case USER = 'cart:user:';

    case GUEST = 'cart:guest:';

    public function getValue(): string
    {
        return $this->value;
    }

}
