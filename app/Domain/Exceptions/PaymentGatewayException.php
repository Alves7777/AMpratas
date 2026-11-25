<?php

namespace App\Domain\Exceptions;

use RuntimeException;

final class PaymentGatewayException extends RuntimeException
{
    public static function notConfigured(): self
    {
        return new self('Mercado Pago ainda não está configurado. Cole o access token de teste no .env.');
    }

    public static function rejected(string $message): self
    {
        return new self($message);
    }

    public static function mismatch(): self
    {
        return new self('Esse pagamento não pertence a este pedido.');
    }
}
