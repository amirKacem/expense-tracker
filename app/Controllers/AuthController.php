<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\AuthInterface;
use App\Contracts\RequestValidatorFatcoryInterface;
use App\DTO\RegisterUserInputs;
use App\Exception\ValidationException;
use App\Validators\LoginRequestValidator;
use App\Validators\RegisterUserRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AuthController
{
    public function __construct(
        private readonly Twig $twig,
        private EntityManagerInterface $entityManager,
        private readonly AuthInterface $auth,
        private readonly RequestValidatorFatcoryInterface $validatorFactory
    ) {
    }

    public function loginView(Request $request, Response $response)
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $this->validatorFactory
             ->make(LoginRequestValidator::class)
             ->validate($data);

        if(empty($this->auth->login($data) === true)) {
            throw new ValidationException(['password' => 'Invalid Email Or Password']);
        }

        return $response->withHeader('Location', '/')->withStatus(302);

    }

    public function registerView(Request $request, Response $response)
    {

        return $this->twig->render($response, 'auth/register.twig');
    }

    public function register(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $this->validatorFactory
             ->make(RegisterUserRequestValidator::class)
             ->validate($data);

        $this->auth->register(
            new RegisterUserInputs($data['name'], $data['email'], $data['password'])
        );

        return $response->withHeader('Location', '/')->withStatus(302);

    }


    public function logout(Request $request, Response $response)
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/')->withStatus(302);
    }



}
