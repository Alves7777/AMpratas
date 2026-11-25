<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AMpratas — Pratas e Acessórios')</title>
    <meta name="description" content="@yield('meta_description', 'Joias em prata 925 com acabamento premium. Alianças, anéis, colares, pulseiras e mais.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="site-shell">
        <header id="site-header" class="site-header">
            <div class="container-luxe header-bar">
                <div class="header-side header-side-left">
                    <button type="button" class="menu-btn" data-toggle-nav aria-label="Menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 7h16M4 12h16M4 17h12"/></svg>
                    </button>
                    <nav class="header-nav">
                        <a href="{{ route('catalog.index') }}" class="nav-link">Loja</a>
                        <a href="{{ route('catalog.category', 'aliancas') }}" class="nav-link">Alianças</a>
                        <a href="{{ route('catalog.category', 'aneis') }}" class="nav-link">Anéis</a>
                    </nav>
                </div>

                <a href="{{ route('home') }}" class="brand-lockup" aria-label="AMpratas — Pratas e Acessórios">
                    <img src="{{ asset('brand/logo-mark-wide.png') }}" alt="AMpratas — Pratas e Acessórios" class="brand-logo">
                </a>

                <div class="header-side header-side-right">
                    <nav class="header-nav">
                        <a href="{{ route('catalog.category', 'colares') }}" class="nav-link">Colares</a>
                        <a href="{{ route('catalog.category', 'pulseiras') }}" class="nav-link">Pulseiras</a>
                        <a href="{{ route('catalog.category', 'relogios') }}" class="nav-link">Relógios</a>
                    </nav>
                    <div class="header-contact">
                        @include('store.partials.contact-icons')
                    </div>
                    <a href="{{ route('cart.index') }}" class="bag-link">
                        Sacola
                        @if(($cartCount ?? 0) > 0)
                            <b>({{ $cartCount }})</b>
                        @endif
                    </a>
                </div>
            </div>

            <div id="mobile-nav" class="mobile-nav hidden lg:hidden">
                <div class="container-luxe flex flex-col py-2 text-base">
                    <a class="py-3" href="{{ route('catalog.index') }}">Loja</a>
                    <a class="py-3" href="{{ route('catalog.category', 'aliancas') }}">Alianças</a>
                    <a class="py-3" href="{{ route('catalog.category', 'aneis') }}">Anéis</a>
                    <a class="py-3" href="{{ route('catalog.category', 'colares') }}">Colares</a>
                    <a class="py-3" href="{{ route('catalog.category', 'pulseiras') }}">Pulseiras</a>
                    <a class="py-3" href="{{ route('catalog.category', 'brincos') }}">Brincos</a>
                    <a class="py-3" href="{{ route('catalog.category', 'relogios') }}">Relógios</a>
                    <div class="mobile-contact">
                        @include('store.partials.contact-icons')
                        <a href="tel:+558596744989">+55 85 9674-4989</a>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-ink text-pearl text-center text-sm py-2.5">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-900 text-white text-center text-sm py-2.5">{{ session('error') }}</div>
        @endif

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="container-luxe">
                <div class="footer-grid">
                    <p>
                        Prata com fabricação própria em Fortaleza. Embalagem de presente e atendimento direto.
                    </p>
                    <div>
                        <p class="footer-label">Atendimento</p>
                        <div class="contact-links">
                            @include('store.partials.contact-icons')
                        </div>
                        <a class="contact-phone" href="tel:+558596744989">+55 85 9674-4989</a>
                        <span>Fortaleza, CE</span>
                    </div>
                    <div>
                        <p class="footer-label">Loja</p>
                        <a href="{{ route('catalog.index') }}">Catálogo</a>
                        <a href="{{ route('cart.index') }}">Sacola</a>
                        <a href="/admin">Área do lojista</a>
                    </div>
                </div>
            </div>
            <div class="footer-copy">© {{ date('Y') }} AMpratas</div>
        </footer>
    </div>
</body>
</html>
