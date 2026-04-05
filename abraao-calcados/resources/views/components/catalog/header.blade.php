{{-- Header Component --}}

{{-- Top utility bar --}}
<div class="bg-neutral-900 text-neutral-400 text-[11px]">
    <div class="max-w-7xl mx-auto px-4 py-2">
        <div class="flex items-center justify-between gap-3">

            <div class="flex items-center gap-3 min-w-0">
                <span class="hidden sm:inline-flex items-center gap-1.5 truncate">
                    <svg class="w-3 h-3 text-neutral-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ config('store.address') }}
                </span>
                <span class="hidden sm:inline text-neutral-700">·</span>
                <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                    <svg class="w-3 h-3 text-neutral-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Seg–Sáb, 8h–18h
                </span>
            </div>

            <a
                href="tel:+55{{ preg_replace('/\D/', '', config('store.phone')) }}"
                class="inline-flex items-center gap-1.5 whitespace-nowrap hover:text-white transition-colors"
            >
                <svg class="w-3 h-3 text-neutral-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                {{ config('store.phone') }}
            </a>

        </div>
    </div>
</div>

{{-- Main header --}}
<header class="bg-gradient-to-b from-amber-50/40 to-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-5 md:py-6">
        <div class="flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="/" class="block flex-shrink-0 w-40 md:w-52">
                <img
                    src="{{ asset('imagens/logo.png') }}"
                    alt="{{ config('store.name') }}"
                    class="w-full h-auto"
                    onerror="this.src='{{ asset('imagens/logo.svg') }}';"
                    width="208"
                    height="80"
                >
            </a>

            {{-- Centro: tagline + localidade --}}
            <div class="hidden md:flex flex-col items-center gap-1 flex-1 text-center">
                <p class="text-sm text-stone-500 italic leading-none">
                    As melhores marcas com o menor preço
                </p>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-brand-700 uppercase tracking-widest">
                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    Rio Branco · AC
                </span>
            </div>

            {{-- CTA WhatsApp --}}
            <a
                href="https://wa.me/{{ config('store.whatsapp_number') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="flex-shrink-0 inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#20c05c] active:bg-[#1aa84f] text-white font-bold text-sm py-2.5 px-4 md:px-5 rounded-xl transition-colors shadow-sm hover:shadow-md hover:shadow-[#25D366]/25"
            >
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span class="hidden sm:inline">Falar no WhatsApp</span>
                <span class="sm:hidden">WhatsApp</span>
            </a>

        </div>
    </div>
</header>
