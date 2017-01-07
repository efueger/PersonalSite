<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;

abstract class BaseController
{
    protected $db;
    protected $view;
    protected $flash;
    protected $logger;
    protected $router;

    public function __construct(ContainerInterface $c)
    {
        $this->view = $c->view;
        $this->db = $c->db;
        $this->flash = $c->flash;
        $this->logger = $c->logger;
        $this->router = $c->router;
    }
}
