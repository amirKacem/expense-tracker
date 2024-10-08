<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/configs/path_constants.php';

$dotEnv = Dotenv::createImmutable(__DIR__);
$dotEnv->load();

return require CONFIG_PATH . '/container/container.php';
