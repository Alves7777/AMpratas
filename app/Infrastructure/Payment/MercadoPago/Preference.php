<?php

namespace App\Infrastructure\Payment\MercadoPago;

use App\Domain\Enums\PaymentMethod;

/**
 * Monta o corpo de uma preferência do Checkout Pro.
 * Cada método devolve a mesma instância para encadear a montagem.
 */
final class Preference
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(private array $payload) {}

    public static function create(): self
    {
        return new self([
            'items' => [],
            'binary_mode' => false,
        ]);
    }

    public function item(string $title, int $quantity, float $unitPrice): self
    {
        $this->payload['items'][] = [
            'title' => $title,
            'quantity' => max(1, $quantity),
            'currency_id' => 'BRL',
            'unit_price' => round($unitPrice, 2),
        ];

        return $this;
    }

    public function payer(string $name, string $email): self
    {
        $parts = preg_split('/\s+/', trim($name), 2) ?: [];

        $this->payload['payer'] = [
            'name' => $parts[0] ?? $name,
            'surname' => $parts[1] ?? ($parts[0] ?? $name),
            'email' => $email,
        ];

        return $this;
    }

    public function externalReference(string $reference): self
    {
        $this->payload['external_reference'] = $reference;

        return $this;
    }

    public function backUrls(string $success, string $pending, string $failure): self
    {
        $this->payload['back_urls'] = [
            'success' => $success,
            'pending' => $pending,
            'failure' => $failure,
        ];

        return $this;
    }

    public function notificationUrl(?string $url): self
    {
        if (filled($url)) {
            $this->payload['notification_url'] = $url;
        }

        return $this;
    }

    public function only(PaymentMethod $method): self
    {
        $excluded = match ($method) {
            PaymentMethod::Pix => ['credit_card', 'debit_card', 'ticket'],
            PaymentMethod::CreditCard => ['debit_card', 'ticket', 'bank_transfer'],
            PaymentMethod::DebitCard => ['credit_card', 'ticket', 'bank_transfer'],
            PaymentMethod::ManualWhatsapp => [],
        };

        $this->payload['payment_methods'] = [
            'excluded_payment_types' => array_map(
                fn (string $id): array => ['id' => $id],
                $excluded,
            ),
            'installments' => $method === PaymentMethod::CreditCard ? 12 : 1,
        ];

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
