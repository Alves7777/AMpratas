@extends('layouts.store')

@section('title', 'Pedido confirmado — AMpratas')

@section('content')
<section class="container-luxe py-16 lg:py-24">
    <div class="mx-auto max-w-3xl text-center">
        <p class="eyebrow">Pedido recebido</p>
        <h1 class="mt-3 font-display text-5xl sm:text-6xl">Obrigada pela preferência</h1>
        <p class="mt-5 text-mute leading-relaxed">
            Seu pedido <strong class="text-ink">{{ $order->order_number }}</strong> foi registrado.
            @if(session('payment_message'))
                {{ session('payment_message') }}
            @else
                Em breve a AMpratas entra em contato para combinar pagamento e entrega.
            @endif
        </p>
    </div>

    <div class="surface mx-auto mt-10 max-w-3xl p-7 text-left">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-display text-3xl">Itens</h2>
            <span class="rounded-full bg-mist/80 px-3 py-1 text-[11px] tracking-[0.14em] uppercase text-mute">
                {{ $order->payment_status?->label() ?? 'Pendente' }}
            </span>
        </div>
        <ul class="mt-5 space-y-3 text-sm">
            @foreach($order->items as $item)
                <li class="flex justify-between gap-3 border-b border-ink/5 pb-3">
                    <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span>R$ {{ number_format((float) $item->line_total, 2, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
        <p class="mt-5 text-right font-display text-3xl">Total R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
    </div>

    <div class="mt-10 flex flex-wrap justify-center gap-3">
        <a href="{{ route('catalog.index') }}" class="btn-ghost">Voltar à loja</a>
        @if(session('whatsapp_link'))
            <a href="{{ session('whatsapp_link') }}" target="_blank" class="btn-primary">Abrir WhatsApp</a>
        @endif
    </div>
</section>
@endsection
