<?php

namespace App\Middleware;

class BaseMiddleware
{
    protected $router;

    public function __construct($c)
    {
        $this->router = $c->router;
    }
}
