<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AgentNotFoundException extends BaseException
{

    public function __construct()
    {
        parent::__construct(__('messages.agent_not_found'), Response::HTTP_BAD_REQUEST);
    }
}
