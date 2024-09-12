<?php

declare(strict_types=1);

namespace App\Contracts;

interface RequestValidatorFatcoryInterface
{
    public function make(string $type): ValidatorInterface;
}
