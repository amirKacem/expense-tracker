<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MethodOverrideMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $methodHeader = $request->getHeaderLine('X-Http-Method-Override');

        if($methodHeader) {
            $request = $request->withMethod($methodHeader);
        } elseif (strtoupper($request->getMethod()) === 'POST') {
            $body = $request->getParsedBody();

            if(is_array($body) && empty($body['_METHOD']) === false) {
                $request = $request->withMethod($methodHeader);
            }

            if($request->getBody()->eof()) {
                $request->getBody()->rewind();
            }
        }
        return $handler->handle($request);
    }
}
