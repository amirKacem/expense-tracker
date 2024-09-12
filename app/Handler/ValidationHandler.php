<?php

declare(strict_types=1);

namespace App\Handler;

use App\Contracts\SessionInterface;
use App\Services\RequestService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

class ValidationHandler implements ErrorHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private readonly SessionInterface $session,
        private readonly RequestService $requestService,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();
        $referer = $this->requestService->getReferer($request);
        $oldData = $request->getParsedBody();
        $sensitiveData = ['password','confirmPassword'];
        $this->session->flash('errors', $exception->errors);
        $this->session->flash('old', array_diff_key($oldData, array_flip($sensitiveData)));

        return  $response->withHeader('Location', $referer)->withStatus(302);
    }
}
