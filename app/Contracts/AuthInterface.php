<?php

namespace App\Contracts;

use App\DTO\RegisterUserInputs;

interface AuthInterface
{
    public function user(): ?UserInterface;

    public function login(array $credentials): bool;

    public function authenticate(UserInterface $user): void;

    public function register(RegisterUserInputs $inputs): UserInterface;

    public function checkCredentials(UserInterface $user, array $credentials): bool;

    public function logout();
}
