@extends('layouts.store')

@section('title', isset($activeCategory) ? $activeCategory->name.' — AMpratas' : 'Loja — AMpratas')

@section('content')
<section class="container-luxe pb-2 pt-8 lg:pt-12">
    <p class="text-sm text-mute">Loja</p>
    <h1 class="section-title mt-2">{{ $activeCategory->name ?? 'Todas as joias' }}</h1>
    @if(!empty($activeCategory?->description))
        <p class="mt-3 max-w-xl text-ink/70 leading-relaxed">{{ $activeCategory->description }}</p>
    @endif
</section>

<section class="container-luxe py-8 lg:py-10">
    <form method="GET" action="{{ route('catalog.index') }}" class="mb-10 grid gap-4 border-y border-ink/10 py-5 lg:grid-cols-[1.4fr_1fr_1fr_auto] lg:items-end">

        <div>
            <label class="eyebrow">Buscar</label>
            <input type="text" name="q" value="{{ $search }}" placeholder="Nome da peça..." class="input-luxe mt-2">
        </div>
        <div>
            <label class="eyebrow">Categoria</label>
            <select name="categoria" class="input-luxe mt-2">
                <option value="">Todas</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected($currentCategory === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="eyebrow">Ordenar</label>
            <select name="ordenar" class="input-luxe mt-2">
                <option value="recentes" @selected($sort === 'recentes')>Mais recentes</option>
                <option value="menor-preco" @selected($sort === 'menor-preco')>Menor preço</option>
                <option value="maior-preco" @selected($sort === 'maior-preco')>Maior preço</option>
            </select>
        </div>
        <button class="btn-primary h-[52px]">Filtrar</button>
    </form>

    @if($products->isEmpty())
        <div class="panel-soft py-24 text-center">
            <p class="font-display text-3xl">Nenhuma peça encontrada</p>
            <a href="{{ route('catalog.index') }}" class="btn-ghost mt-6">Limpar filtros</a>
        </div>
    @else
        <div>
            <div class="grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($products as $product)
                    @include('store.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-14">{{ $products->links() }}</div>
        </div>
    @endif
</section>

@endsection
