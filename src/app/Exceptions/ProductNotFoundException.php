<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProductNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(__('messages.product_not_found'), Response::HTTP_BAD_REQUEST);
    }
}
