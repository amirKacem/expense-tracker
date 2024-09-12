<?php

declare(strict_types=1);

namespace App\Validators;

use App\Contracts\ValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class LoginRequestValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $validator = new Validator($data);
        $validator->rule('required', ['email', 'password']);
        $validator->rule('email', 'email');

        if($validator->validate() === false) {
            throw new ValidationException($validator->errors());
        }

        return $data;
    }
}
