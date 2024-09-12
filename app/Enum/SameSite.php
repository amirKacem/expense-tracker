<?php

declare(strict_types=1);

namespace App\Enum;

enum SameSite: string
{
    case lax = 'lax';
    case None = 'none';
    case Strict = 'strict';
}