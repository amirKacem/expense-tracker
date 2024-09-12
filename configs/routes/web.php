<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\Routing\RouteCollectorProxy;

return function (\Slim\App $app) {

    $app->get('/', [HomeController::class,'index'])->add(AuthMiddleware::class);
    $app->post('/logout', [AuthController::class,'logout'])->add(AuthMiddleware::class);


    $app->group('', function (RouteCollectorProxy $group) {

        $group->get('/login', [AuthController::class,'loginView']);
        $group->get('/register', [AuthController::class,'registerView']);
        $group->post('/login', [AuthController::class,'login']);
        $group->post('/register', [AuthController::class,'register']);
    })->add(GuestMiddleware::class);


    $app->group('/categories', function (RouteCollectorProxy $group) {
        $group->get('', [CategoryController::class,'index']);
        $group->post('', [CategoryController::class,'store']);
        $group->delete('/{id}', [CategoryController::class,'delete']);

    })->add(AuthMiddleware::class);

};
