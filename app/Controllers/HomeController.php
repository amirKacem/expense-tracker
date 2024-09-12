<?php

namespace App\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    public function __construct(private Twig $twig, private EntityManagerInterface $entityManager)
    {
    }


    public function index(Request $request, Response $response)
    {
        $user = $request->getAttribute('user');
        return $this->twig->render($response, 'index.twig');
    }
}
