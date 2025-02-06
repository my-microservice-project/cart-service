<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class CartItemNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(__('messages.product_not_found_in_cart'), Response::HTTP_BAD_REQUEST);
    }
}
