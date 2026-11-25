<?php

namespace App\Application\DTOs;

use App\Domain\Enums\PaymentMethod;

final readonly class CheckoutCustomerData
{
    public function __construct(
        public string $customerName,
        public string $customerEmail,
        public string $customerWhatsapp,
        public ?string $customerPhone,
        public ?string $shippingCity,
        public ?string $shippingState,
        public ?string $shippingCep,
        public ?string $shippingAddress,
        public ?string $notes,
        public PaymentMethod $paymentMethod,
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            customerName: $data['customer_name'],
            customerEmail: $data['customer_email'],
            customerWhatsapp: $data['customer_whatsapp'],
            customerPhone: $data['customer_phone'] ?? null,
            shippingCity: $data['shipping_city'] ?? null,
            shippingState: $data['shipping_state'] ?? null,
            shippingCep: $data['shipping_cep'] ?? null,
            shippingAddress: $data['shipping_address'] ?? null,
            notes: $data['notes'] ?? null,
            paymentMethod: PaymentMethod::from($data['payment_method'] ?? PaymentMethod::ManualWhatsapp->value),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toOrderAttributes(): array
    {
        return [
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_whatsapp' => $this->customerWhatsapp,
            'customer_phone' => $this->customerPhone,
            'shipping_city' => $this->shippingCity,
            'shipping_state' => $this->shippingState,
            'shipping_cep' => $this->shippingCep,
            'shipping_address' => $this->shippingAddress,
            'notes' => $this->notes,
            'payment_method' => $this->paymentMethod,
        ];
    }

    public function whatsappDigits(): string
    {
        return (string) preg_replace('/\D+/', '', $this->customerWhatsapp);
    }
}
