<?php

namespace App\Contracts;

use App\DTO\RegisterUserInputs;

interface UserProviderInterface
{
    public function getById(int $userId): ?UserInterface;

    public function getByCredentials(array $credentials): ?UserInterface;

    public function createUser(RegisterUserInputs $inputs): UserInterface;
}
