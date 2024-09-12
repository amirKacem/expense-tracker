<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFatcoryInterface;
use App\Services\CategoryService;
use App\Validators\CategoryRequestValidator;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CategoryController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFatcoryInterface $requestValidatorFactory,
        private readonly CategoryService $categoryService
    ) {

    }


    public function index(Request $request, Response $response): Response
    {

        return $this->twig->render(
            $response,
            'categories/index.twig',
            ['categories' => $this->categoryService->getAll()]
        );
    }


    public function store(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory
            ->make(CategoryRequestValidator::class)
            ->validate($request->getParsedBody());

        $this->categoryService->create([
            'name' => $data['name'],
            'user' => $request->getAttribute('user')
        ]);


        return $response->withHeader('Location', '/categories')->withStatus(302);
    }



    public function delete(Request $request, Response $response, array $args): Response
    {

        $this->categoryService->delete((int) $args['id']);

        return $this->twig->render($response, 'categories/index.twig');
    }



}
