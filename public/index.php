<?php

use App\Twig\CsrfExtension;
use Slim\App;
use Slim\Views\Twig;

$container = require __DIR__ . '/../bootstrap.php';

$container->get(App::class)->run();
