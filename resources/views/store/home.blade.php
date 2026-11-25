@extends('layouts.store')

@section('title', 'AMpratas — Pratas e Acessórios')

@section('content')
@php
    $wanted = ['par-aliancas-prata-950', 'par-aliancas-boleada-ouro', 'colar-com-nome'];
    $pool = $featured->concat($latest)->unique('id');
    $sale = collect($wanted)->map(fn ($slug) => $pool->firstWhere('slug', $slug))->filter();
    $poster = $sale->first();
    $offers = $sale->skip(1);
@endphp

@if($poster)
<section class="container-luxe shop-window">
    <a href="{{ route('product.show', $poster->slug) }}" class="shop-hero">
        <img src="{{ $poster->imageUrl() }}" alt="{{ $poster->name }}">
        <div class="shop-hero-copy">
            <p>{{ $poster->category?->name }} · pronta entrega</p>
            <h1>{{ $poster->name }}</h1>
            <p class="poster-price">{{ $poster->formattedPrice() }}</p>
            @if($poster->hasDiscount())
                <p class="shop-compare">R$ {{ number_format((float) $poster->compare_at_price, 2, ',', '.') }}</p>
            @endif
            <p class="shop-hero-text">{{ $poster->short_description }}</p>
            <span class="btn-primary">Comprar</span>
        </div>
    </a>

    @if($offers->isNotEmpty())
        <div class="shop-side">
            @foreach($offers as $offer)
                <a href="{{ route('product.show', $offer->slug) }}" class="side-offer">
                    <img src="{{ $offer->imageUrl() }}" alt="{{ $offer->name }}">
                    <div>
                        <p>{{ $offer->category?->name }}</p>
                        <h2>{{ $offer->name }}</h2>
                        <p class="side-price">{{ $offer->formattedPrice() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>
@endif

<section class="container-luxe">
    <ul class="promises">
        <li>
            <strong>Fabricação própria</strong>
            <span>A peça sai da nossa bancada, em Fortaleza.</span>
        </li>
        <li>
            <strong>Garantia na cor</strong>
            <span>O brilho da prata permanece com você.</span>
        </li>
        <li>
            <strong>Todo o Brasil</strong>
            <span>Enviamos com embalagem de presente.</span>
        </li>
    </ul>
</section>

<section class="container-luxe pb-6 pt-14 lg:pt-20">
    <div class="mb-6 flex items-end justify-between gap-6">
        <h2 class="section-title">O que tem na loja</h2>
        <a href="{{ route('catalog.index') }}" class="text-link">Tudo</a>
    </div>

    <div class="cat-scroll">
        @foreach($categories as $category)
            <a href="{{ route('catalog.category', $category->slug) }}" class="cat-card">
                <img src="{{ $category->imageUrl() ?? asset('images/hero-aliancas.jpg') }}" alt="{{ $category->name }}">
                <span>{{ $category->name }}</span>
            </a>
        @endforeach
    </div>

    <div class="cat-board">
        @foreach($categories as $category)
            <a href="{{ route('catalog.category', $category->slug) }}">
                <img src="{{ $category->imageUrl() ?? asset('images/hero-aliancas.jpg') }}" alt="">
                <span>{{ $category->name }}</span>
            </a>
        @endforeach
    </div>
</section>

@php
    $lead = $featured->first();
    $rest = $featured->skip(1)->take(5);
@endphp

@if($lead)
<section class="container-luxe py-16 lg:py-24">
    <h2 class="section-title mb-8">Na vitrine</h2>
    <div class="shelf">
        <a href="{{ route('product.show', $lead->slug) }}" class="shelf-lead">
            <img src="{{ $lead->imageUrl() }}" alt="{{ $lead->name }}">
            <div class="mt-4 flex items-baseline justify-between gap-4">
                <h3 class="font-display text-3xl leading-none">{{ $lead->name }}</h3>
                <span class="text-sm">{{ $lead->formattedPrice() }}</span>
            </div>
            <p class="mt-2 text-sm text-mute">{{ $lead->category?->name }}</p>
        </a>

        <div>
            @foreach($rest as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="shelf-item">
                    <img src="{{ $product->imageUrl() }}" alt="">
                    <span>
                        <span class="block font-display text-xl leading-tight">{{ $product->name }}</span>
                        <span class="mt-1 block text-sm text-mute">{{ $product->category?->name }}</span>
                    </span>
                    <span class="shelf-price text-sm">{{ $product->formattedPrice() }}</span>
                </a>
            @endforeach
            <a href="{{ route('catalog.index') }}" class="text-link mt-6">Ver o restante da coleção</a>
        </div>
    </div>
</section>
@endif

<section class="container-luxe pb-4">
    <div class="grid items-center gap-8 border-y border-ink/10 py-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-14">
        <img src="{{ asset('images/ig/05-nov21.jpg') }}" alt="Pulseiras AMpratas na caixa" class="soft-photo">
        <div>
            <h2 class="section-title">Pulseiras pra usar juntas ou sozinhas.</h2>
            <p class="mt-4 max-w-md text-ink/75 leading-relaxed">
                Correntes finas, trevos e peças de empilhar. O brilho fica no metal, sem exagero.
            </p>
            <a href="{{ route('catalog.category', 'pulseiras') }}" class="text-link mt-6">Ver pulseiras</a>
        </div>
    </div>
</section>

<section class="container-luxe py-12 lg:py-16">
    <div class="mb-6 flex items-end justify-between gap-4">
        <h2 class="section-title">No Instagram</h2>
        <a href="https://www.instagram.com/ampratass__/" class="text-link" target="_blank" rel="noopener">@ampratass__</a>
    </div>
    <div class="ig-grid">
        @foreach([
            ['images/ig/05-nov21.jpg', 'Pulseiras na caixa'],
            ['images/ig/04-aug14-2025.jpg', 'Colar com fotogravação'],
            ['images/ig/07-nov10.jpg', 'Alianças personalizadas'],
            ['images/ig/03-sep22.jpg', 'Alianças em uso'],
            ['images/ig/02-aug13.jpg', 'Clientes AMpratas'],
        ] as [$photo, $alt])
            <a href="https://www.instagram.com/ampratass__/" target="_blank" rel="noopener">
                <img src="{{ asset($photo) }}" alt="{{ $alt }}">
            </a>
        @endforeach
    </div>
</section>

<section class="container-luxe py-16 lg:py-20">
    <div class="mb-8 flex items-end justify-between">
        <h2 class="section-title">Chegou agora</h2>
    </div>
    <div class="grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($latest as $product)
            @include('store.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endsection
