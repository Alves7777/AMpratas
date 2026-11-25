<?php

namespace App\Application\Services;

final class OrderNumberGenerator
{
    public function generate(): string
    {
        return sprintf(
            'AM-%s-%s',
            now()->format('Ymd'),
            strtoupper(substr(uniqid('', true), -5))
        );
    }
}
