<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\CategoryService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CategoryController
{
    public function __construct(private CategoryService $categoryService)
    {

    }

    public function findAll(Request $request,Response $response)
    {   
        $categories = $this->categoryService->getALL();
        $response = $response->withHeader('Content-Type','application/json');
        $response->getBody()->write(json_encode([['id' => 1 ,'title' => 'test']]));
        return $response;
    }
}
