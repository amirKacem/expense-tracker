<?php

declare(strict_types=1);

namespace App\Auth;

use App\Contracts\UserInterface;
use App\Contracts\UserProviderInterface;
use App\DTO\RegisterUserInputs;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getById(int $userId): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->find($userId);
    }

    public function getByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)
               ->findOneBy(['email' => $credentials['email']]);
    }

    public function createUser(RegisterUserInputs $data): UserInterface
    {
        $user = new User();
        $user->setName($data->name);
        $user->setEmail($data->email);
        $user->setPassword(password_hash($data->password, PASSWORD_BCRYPT));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
