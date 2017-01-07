<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class PagesController extends BaseController
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'index.twig');
    }
}