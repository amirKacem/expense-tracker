<?php

use App\Controllers\Api\CategoryController;
use Slim\Routing\RouteCollectorProxy;

return function (\Slim\App $app) {
    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        $group->get('/categories', [CategoryController::class,'findAll']);
        
    });

};
