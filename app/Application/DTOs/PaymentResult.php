<?php

namespace App\Application\DTOs;

use App\Domain\Enums\PaymentStatus;

final readonly class PaymentResult
{
    public function __construct(
        public PaymentStatus $status,
        public ?string $transactionId = null,
        public ?string $checkoutUrl = null,
        public ?string $message = null,
        public array $meta = [],
    ) {}

    public static function pendingManual(?string $message = null): self
    {
        return new self(
            status: PaymentStatus::AwaitingPayment,
            message: $message ?? 'Aguardando confirmação manual.',
        );
    }

    public static function redirect(string $checkoutUrl, ?string $transactionId = null): self
    {
        return new self(
            status: PaymentStatus::AwaitingPayment,
            transactionId: $transactionId,
            checkoutUrl: $checkoutUrl,
            message: 'Redirecionando para o pagamento.',
        );
    }

    public function withMessage(string $message): self
    {
        return new self($this->status, $this->transactionId, $this->checkoutUrl, $message, $this->meta);
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    public function withMeta(array $meta): self
    {
        return new self($this->status, $this->transactionId, $this->checkoutUrl, $this->message, $meta);
    }
}
