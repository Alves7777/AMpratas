<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'material',
        'images',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'stock' => 'integer',
            'images' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (blank($product->slug) && filled($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coverImage(): ?string
    {
        $images = $this->images ?? [];

        return $images[0] ?? null;
    }

    public function imageUrl(?string $path = null): ?string
    {
        $path ??= $this->coverImage();

        if (! $path) {
            return asset('images/placeholder-product.jpg');
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk(config('filesystems.media'))->url($path);
    }

    public function galleryUrls(): array
    {
        return collect($this->images ?? [])
            ->map(fn (string $path) => $this->imageUrl($path))
            ->filter()
            ->values()
            ->all();
    }

    public function formattedPrice(): string
    {
        return 'R$ '.number_format((float) $this->price, 2, ',', '.');
    }

    public function hasDiscount(): bool
    {
        return $this->compare_at_price && $this->compare_at_price > $this->price;
    }
}
