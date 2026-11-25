@extends('layouts.store')

@section('title', 'Checkout — AMpratas')

@section('content')
<section class="container-luxe py-14 lg:py-20">
    <p class="eyebrow">Quase lá</p>
    <h1 class="mt-3 font-display text-5xl sm:text-6xl">Finalizar pedido</h1>
    <p class="mt-4 max-w-2xl text-mute leading-relaxed">
        Preencha seus dados. Pix, crédito e débito abrem o checkout de teste do Mercado Pago. O WhatsApp continua disponível.
    </p>

    <div class="mt-12 grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
        <form method="POST" action="{{ route('checkout.store') }}" class="surface space-y-8 p-6 sm:p-8">
            @csrf

            <div>
                <h2 class="font-display text-3xl">Seus dados</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="eyebrow">Nome completo</label>
                        <input name="customer_name" value="{{ old('customer_name') }}" required class="input-luxe mt-2">
                        @error('customer_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="eyebrow">E-mail</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" required class="input-luxe mt-2">
                    </div>
                    <div>
                        <label class="eyebrow">WhatsApp</label>
                        <input name="customer_whatsapp" value="{{ old('customer_whatsapp') }}" required placeholder="85999999999" class="input-luxe mt-2">
                    </div>
                    <div>
                        <label class="eyebrow">Telefone</label>
                        <input name="customer_phone" value="{{ old('customer_phone') }}" class="input-luxe mt-2">
                    </div>
                    <div>
                        <label class="eyebrow">CEP</label>
                        <input name="shipping_cep" value="{{ old('shipping_cep') }}" class="input-luxe mt-2">
                    </div>
                    <div>
                        <label class="eyebrow">Cidade</label>
                        <input name="shipping_city" value="{{ old('shipping_city') }}" class="input-luxe mt-2">
                    </div>
                    <div>
                        <label class="eyebrow">UF</label>
                        <input name="shipping_state" value="{{ old('shipping_state', 'CE') }}" maxlength="2" class="input-luxe mt-2">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="eyebrow">Endereço</label>
                        <input name="shipping_address" value="{{ old('shipping_address') }}" class="input-luxe mt-2">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="eyebrow">Observações</label>
                        <textarea name="notes" rows="3" class="input-luxe mt-2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="font-display text-3xl">Pagamento</h2>
                <p class="mt-2 text-sm text-mute">Escolha como deseja pagar.</p>
                @if($mercadoPagoSandbox ?? false)
                    <p class="mt-2 text-sm text-mute">Ambiente de teste. No cartão, use o nome do titular APRO para aprovar. O e-mail precisa ser de um comprador de teste, a menos que MERCADOPAGO_TEST_PAYER_EMAIL esteja preenchido.</p>
                @endif
                <div class="mt-5 grid gap-3">
                    @foreach($paymentMethods as $method)
                        <label
                            data-payment-option
                            class="flex cursor-pointer items-start gap-4 rounded-2xl border border-ink/10 bg-pearl/60 p-4 transition hover:border-ink/25 {{ old('payment_method', 'manual_whatsapp') === $method->value ? 'ring-2 ring-forest/40 border-forest/40' : '' }}"
                        >
                            <input
                                type="radio"
                                name="payment_method"
                                value="{{ $method->value }}"
                                class="mt-1"
                                @checked(old('payment_method', 'manual_whatsapp') === $method->value)
                            >
                            <span>
                                <span class="block text-sm font-semibold tracking-wide">{{ $method->label() }}</span>
                                <span class="mt-1 block text-sm text-mute">{{ $method->description() }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('payment_method') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <button class="btn-primary w-full sm:w-auto">Confirmar pedido</button>
        </form>

        <aside class="surface h-fit p-7">
            <p class="eyebrow">Resumo</p>
            <h2 class="mt-2 font-display text-3xl">Seu pedido</h2>
            <ul class="mt-5 space-y-3 text-sm">
                @foreach($items as $item)
                    <li class="flex justify-between gap-3 border-b border-ink/5 pb-3">
                        <span class="text-ink/80">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                        <span>R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-5 flex items-end justify-between">
                <span class="text-mute">Total</span>
                <span class="font-display text-4xl">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
            </div>
        </aside>
    </div>
</section>
@endsection
