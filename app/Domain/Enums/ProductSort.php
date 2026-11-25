<?php

namespace App\Domain\Enums;

enum ProductSort: string
{
    case Latest = 'recentes';
    case PriceAsc = 'menor-preco';
    case PriceDesc = 'maior-preco';

    public static function fromRequest(?string $value): self
    {
        return self::tryFrom((string) $value) ?? self::Latest;
    }
}
