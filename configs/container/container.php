<?php

declare(strict_types=1);

use App\Twig\CsrfExtension;
use DI\ContainerBuilder;
use Slim\Views\Twig;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(CONFIG_PATH . '/container/container_bindings.php');

$container = $containerBuilder->build();

return $container;
