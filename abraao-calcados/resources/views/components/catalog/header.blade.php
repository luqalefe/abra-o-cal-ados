{{-- Header Component: Logo + info compacta --}}
<header class="relative overflow-hidden">
    {{-- Warm white background with subtle texture --}}
    <div class="absolute inset-0 bg-gradient-to-b from-white via-stone-50 to-amber-50/30"></div>
    {{-- Subtle dot pattern using brand amber --}}
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23B45309&quot; fill-opacity=&quot;0.15&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;1.5&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    {{-- Subtle golden glow behind logo area --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-brand-300/[0.08] blur-[100px] rounded-full"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-8 md:py-12">
        <div class="flex flex-col items-center text-center space-y-4">
            {{-- Logo (hero) --}}
            <div class="w-56 md:w-72 mb-1">
                <img 
                    src="{{ asset('imagens/logo.png') }}" 
                    alt="{{ config('store.name') }}" 
                    class="w-full h-auto drop-shadow-[0_2px_12px_rgba(180,83,9,0.12)]"
                    onerror="this.src='{{ asset('imagens/logo.svg') }}';"
                >
            </div>

            {{-- Tagline --}}
            <p class="text-stone-500 text-xs md:text-sm italic">{{ config('store.description') }}</p>

            {{-- Info bar: endereço + telefone — compacto, uma linha --}}
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-[11px] md:text-xs text-stone-500">
                {{-- Endereço --}}
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ config('store.address') }}
                </span>

                <span class="text-brand-300 hidden md:inline">•</span>

                {{-- Telefone como link clicável --}}
                <a href="tel:+5568992607794" class="inline-flex items-center gap-1 hover:text-brand-600 transition-colors">
                    <svg class="w-3 h-3 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ config('store.phone') }}
                </a>
            </div>

            {{-- Promo badge --}}
            <div class="mt-2 inline-flex items-center gap-2 bg-brand-500/10 backdrop-blur-sm border border-brand-500/20 rounded-full px-4 py-1.5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                </span>
                <span class="text-brand-700 text-[10px] font-semibold tracking-wider uppercase">Promoções da Semana</span>
            </div>
        </div>
    </div>

    {{-- Bottom golden accent line --}}
    <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-gradient-to-r from-transparent via-brand-500 to-transparent opacity-40"></div>
</header>
