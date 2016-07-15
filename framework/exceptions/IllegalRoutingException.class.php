<?php
namespace Framework\Exceptions;

use Exception;

class IllegalRoutingException extends Exception
{
    public function __construct()
    {
        parent::__construct("非法路由", 401, null);
    }
}