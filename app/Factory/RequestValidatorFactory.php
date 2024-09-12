<?php

declare(strict_types=1);

namespace App\Factory;

use App\Contracts\RequestValidatorFatcoryInterface;
use App\Contracts\ValidatorInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;

class RequestValidatorFactory implements RequestValidatorFatcoryInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {

    }
    public function make(string $type): ValidatorInterface
    {
        $validator = $this->container->get($type);

        if($validator instanceof ValidatorInterface) {
            return $validator;
        }

        throw new RuntimeException('Failed to instantiate the request validator class');
    }
}
