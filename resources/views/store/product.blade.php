@extends('layouts.store')

@section('title', $product->name.' — AMpratas')
@section('meta_description', $product->short_description ?? $product->name)

@section('content')
<section class="container-luxe py-10 lg:py-16">
    <div class="mb-8 text-sm text-mute">
        <a href="{{ route('home') }}" class="hover:text-ink">Home</a>
        <span class="mx-2 opacity-40">/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}" class="hover:text-ink">{{ $product->category->name }}</a>
        <span class="mx-2 opacity-40">/</span>
        <span class="text-ink/70">{{ $product->name }}</span>
    </div>

    <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
        <div>
            <div class="overflow-hidden rounded-[1.6rem] bg-mist" style="aspect-ratio: 4/5;">
                <img id="main-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            </div>
            @if(count($product->galleryUrls()) > 1)
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach($product->galleryUrls() as $url)
                        <button type="button" class="overflow-hidden rounded-2xl bg-mist/50 transition hover:opacity-80" style="aspect-ratio:1" onclick="document.getElementById('main-image').src='{{ $url }}'">
                            <img src="{{ $url }}" alt="" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="lg:pt-4">
            <p class="eyebrow">{{ $product->category->name }}</p>
            <h1 class="mt-3 font-display text-5xl leading-[1.05] sm:text-6xl">{{ $product->name }}</h1>

            <div class="mt-6 flex items-baseline gap-3">
                <span class="price-display">{{ $product->formattedPrice() }}</span>
                @if($product->hasDiscount())
                    <span class="text-mute line-through">R$ {{ number_format((float) $product->compare_at_price, 2, ',', '.') }}</span>
                @endif
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <span class="rounded-full bg-mist/70 px-3 py-1 text-[11px] tracking-[0.14em] uppercase text-mute">{{ $product->material }}</span>
                <span class="rounded-full bg-mist/70 px-3 py-1 text-[11px] tracking-[0.14em] uppercase text-mute">Estoque {{ $product->stock }}</span>
            </div>

            @if($product->short_description)
                <p class="mt-8 max-w-lg text-base leading-relaxed text-ink/75">{{ $product->short_description }}</p>
            @endif

            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-10 flex flex-wrap items-center gap-3">
                @csrf
                <input id="quantity" type="number" name="quantity" min="1" max="20" value="1" class="input-luxe w-24 text-center">
                <button class="btn-primary">Adicionar à sacola</button>
            </form>

            @if($product->description)
                <div class="mt-12 border-t border-ink/10 pt-8">
                    <h2 class="font-display text-3xl">Detalhes</h2>
                    <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-mute">{{ $product->description }}</div>
                </div>
            @endif
        </div>
    </div>

    @if($related->isNotEmpty())
        <div class="mt-24">
            <h2 class="section-title">Você também pode gostar</h2>
            <div class="mt-10 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($related as $item)
                    @include('store.partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
