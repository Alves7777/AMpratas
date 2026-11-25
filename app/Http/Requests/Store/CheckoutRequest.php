<?php

namespace App\Http\Requests\Store;

use App\Application\DTOs\CheckoutCustomerData;
use App\Domain\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $available = array_map(
            fn (PaymentMethod $method): string => $method->value,
            PaymentMethod::available()
        );

        return [
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'customer_whatsapp' => ['required', 'string', 'max:30'],
            'shipping_city' => ['nullable', 'string', 'max:80'],
            'shipping_state' => ['nullable', 'string', 'max:2'],
            'shipping_cep' => ['nullable', 'string', 'max:12'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'string', Rule::in($available)],
        ];
    }

    public function toDto(): CheckoutCustomerData
    {
        return CheckoutCustomerData::fromValidated($this->validated());
    }
}
