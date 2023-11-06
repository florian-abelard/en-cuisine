<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class TestController extends ControllerResolver
{
    public function indexAction()
    {
        dump('Hello World !!!');
    }
}
