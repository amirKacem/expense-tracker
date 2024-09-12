<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Twig\CsrfExtension;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class CsrfFieldsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Twig $twig,
        private ContainerInterface $container
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $this->twig->addExtension(new CsrfExtension($this->container->get('csrf')));
        return $handler->handle($request);
    }
}
