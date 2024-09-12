<?php

declare(strict_types=1);

use App\Config;
use SLim\App;
use App\Exception\ValidationException;
use App\Handler\ValidationHandler;
use App\Middleware\CsrfFieldsMiddleware;
use App\Middleware\MethodOverrideMiddleware;
use App\Middleware\OldFormDataMiddleware;
use App\Middleware\StartSessionMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {

    $container = $app->getContainer();
    $config = $container->get(Config::class);
    $app->add(MethodOverrideMiddleware::class);
    $app->add('csrf');
    $app->add(CsrfFieldsMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
    $app->add(ValidationErrorsMiddleware::class);
    $app->add(OldFormDataMiddleware::class);
    $app->add(StartSessionMiddleware::class);

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setErrorHandler(
        ValidationException::class,
        ValidationHandler::class
    );

};
