<?php

namespace App\Http\Requests\Store;

use App\Application\DTOs\CatalogFilterData;
use App\Domain\Enums\ProductSort;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CatalogFilterRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:120'],
            'categoria' => ['nullable', 'string', 'max:120'],
            'ordenar' => ['nullable', 'string', Rule::enum(ProductSort::class)],
        ];
    }

    public function toDto(): CatalogFilterData
    {
        return CatalogFilterData::fromArray($this->validated());
    }
}
