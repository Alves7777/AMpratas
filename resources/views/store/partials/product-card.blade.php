<a href="{{ route('product.show', $product->slug) }}" class="product-card group">
    <div class="product-media">
        <img
            src="{{ $product->imageUrl() }}"
            alt="{{ $product->name }}"
            loading="lazy"
        >
        @if($product->hasDiscount())
            <span class="absolute left-3 top-3 rounded-full bg-pearl px-2.5 py-1 text-xs text-ink">
                Oferta
            </span>
        @endif
    </div>
    <div class="card-meta mt-3 flex items-start justify-between gap-3">
        <div>
            <p class="text-xs text-mute">{{ $product->category?->name }}</p>
            <h3 class="mt-1 font-display text-[1.35rem] leading-tight">{{ $product->name }}</h3>
        </div>
        <div class="price card-price pt-4 text-right whitespace-nowrap">
            <span>{{ $product->formattedPrice() }}</span>
            @if($product->hasDiscount())
                <span class="mt-0.5 block text-xs text-mute line-through">R$ {{ number_format((float) $product->compare_at_price, 2, ',', '.') }}</span>
            @endif
        </div>
    </div>
</a>
