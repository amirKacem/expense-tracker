<?php

declare(strict_types=1);

namespace App\Auth;

use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderInterface;
use App\DTO\RegisterUserInputs;
use App\Entity\User;

class Auth implements AuthInterface
{
    private ?UserInterface $user = null;

    public function __construct(
        private readonly UserProviderInterface $userProvider,
        private readonly SessionInterface $session
    ) {
    }

    public function user(): ?UserInterface
    {
        if($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user');

        if(empty($userId) === true) {
            return null;
        }

        $this->user = $this->userProvider->getById($userId);

        return $this->user;

    }

    public function login(?array $credentials): bool
    {
        $user = $this->userProvider->getByCredentials($credentials);
        if(empty($user) === true
        || $this->checkCredentials($user, $credentials)
        ) {
            return false;
        }

        $this->authenticate($user);

        return true;
    }

    public function checkCredentials(UserInterface $user, array $credentials): bool
    {
        return password_verify($credentials['password'], $user->getPassword()) === false;
    }

    public function register(RegisterUserInputs $inputs): UserInterface
    {
        $user = $this->userProvider->createUser($inputs);

        $this->authenticate($user);

        return $user;
    }

    public function authenticate(UserInterface $user): void
    {
        $this->session->regenerate();

        $this->session->set('user', $user->getId());

        $this->user = $user;
    }

    public function logout()
    {
        $this->session->delete('user');
        $this->session->regenerate();

        $this->user = null;
    }

}
