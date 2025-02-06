<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ProductStockNotEnoughException extends BaseException
{
    public function __construct()
    {
        parent::__construct(__('messages.stock_not_enough'), Response::HTTP_BAD_REQUEST);
    }
}
