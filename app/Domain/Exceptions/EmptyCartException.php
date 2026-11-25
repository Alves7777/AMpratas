<?php

namespace App\Domain\Exceptions;

use RuntimeException;

final class EmptyCartException extends RuntimeException
{
    public static function make(): self
    {
        return new self('Seu carrinho está vazio.');
    }
}
