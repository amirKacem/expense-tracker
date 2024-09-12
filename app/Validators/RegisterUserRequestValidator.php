<?php

declare(strict_types=1);

namespace App\Validators;

use App\Contracts\ValidatorInterface;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManager;
use Valitron\Validator;

class RegisterUserRequestValidator implements ValidatorInterface
{
    public function __construct(private EntityManager $entityManager)
    {

    }
    public function validate(array $data): array
    {

        $v = new Validator($data);
        $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'confirmPassword', 'password');

        $v->rule(function ($field, $value, $params, $fields) {
            return $this->entityManager
            ->getRepository(User::class)
            ->count(['email' => $value]) === 0;
        }, "email")->message("User with the given email alerday exist");

        if($v->validate() === false) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }

}
