<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class OldFormDataMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Twig $twig,
        private readonly SessionInterface $session,
        private ContainerInterface $container
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $old = $this->session->getFlash('old');
        if(empty($old) === false) {
            $this->twig->getEnvironment()->addGlobal('old', $old);
        }

        return $handler->handle($request);
    }
}
