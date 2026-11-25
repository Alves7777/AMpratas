@extends('layouts.store')

@section('title', 'Sacola — AMpratas')

@section('content')
<section class="container-luxe py-8 lg:py-12">
    <p class="eyebrow">Checkout</p>
    <h1 class="mt-2 font-display text-4xl sm:text-5xl">Sua sacola</h1>

    @if(empty($items))
        <div class="surface mt-10 px-8 py-20 text-center">
            <p class="font-display text-3xl">Sua sacola está vazia</p>
            <p class="mt-3 text-mute">Explore a coleção e encontre a peça certa.</p>
            <a href="{{ route('catalog.index') }}" class="btn-primary mt-8">Continuar comprando</a>
        </div>
    @else
        <div class="mt-10 grid gap-8 lg:grid-cols-[1.4fr_0.8fr]">
            <div class="space-y-4">
                @foreach($items as $item)
                    <div class="surface flex flex-col gap-5 p-5 sm:flex-row sm:items-center">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-28 w-28 rounded-2xl object-cover bg-mist">
                        <div class="flex-1">
                            <a href="{{ route('product.show', $item['slug']) }}" class="font-display text-2xl leading-tight">{{ $item['name'] }}</a>
                            <p class="mt-1 text-sm text-mute">R$ {{ number_format($item['price'], 2, ',', '.') }}</p>
                        </div>
                        <form action="{{ route('cart.update', $item['product_id']) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" min="0" max="20" value="{{ $item['quantity'] }}" class="input-luxe w-20 text-center py-2">
                            <button class="text-[10px] tracking-[0.16em] uppercase text-mute hover:text-ink">Atualizar</button>
                        </form>
                        <div class="min-w-24 text-right font-medium">
                            R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                        </div>
                        <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-[10px] tracking-[0.16em] uppercase text-mute hover:text-red-700">Remover</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <aside class="surface h-fit p-7">
                <p class="eyebrow">Resumo</p>
                <div class="mt-4 flex items-end justify-between">
                    <span class="text-mute">Subtotal</span>
                    <span class="font-display text-4xl">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>
                <p class="mt-3 text-sm text-mute">Frete e pagamento na próxima etapa.</p>
                <a href="{{ route('checkout.index') }}" class="btn-primary mt-8 w-full">Finalizar pedido</a>
                <a href="{{ route('catalog.index') }}" class="btn-ghost mt-3 w-full">Continuar comprando</a>
                <form action="{{ route('cart.clear') }}" method="POST" class="mt-3" onsubmit="return confirm('Limpar toda a sacola?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-center text-[11px] tracking-[0.18em] uppercase text-mute transition hover:text-red-700 py-3">
                        Limpar sacola
                    </button>
                </form>
            </aside>
        </div>
    @endif
</section>
@endsection
