<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCartRequest extends FormRequest
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
        return [
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ];
    }

    public function quantity(): int
    {
        return (int) $this->validated('quantity');
    }
}
