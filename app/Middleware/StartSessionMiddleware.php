<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\SessionInterface;
use App\Exception\SessionException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StartSessionMiddleware implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {

    }
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {


        $this->session->start();

        $response = $handler->handle($request);

        if($request->getMethod() === 'GET') {
            $this->session->set('previousUrl', (string) $request->getUri());
        }

        $this->session->save();

        return $response;
    }
}
