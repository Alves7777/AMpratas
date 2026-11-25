<?php

namespace App\Domain\Exceptions;

use RuntimeException;

final class InactiveProductException extends RuntimeException
{
    public static function make(): self
    {
        return new self('Produto indisponível.');
    }
}
