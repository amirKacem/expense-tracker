<?php

declare(strict_types=1);

namespace App\Validators;

use App\Contracts\ValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class CategoryRequestValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $validator = new Validator($data);
        $validator->rule('required', 'name');
        $validator->rule('lengthMax', 'name', 50);


        if($validator->validate() === false) {
            throw new ValidationException($validator->errors());
        }

        return $data;
    }
}
