{{-- Footer Component --}}
<footer class="bg-neutral-950 text-neutral-500 mt-0">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col items-center gap-4">
            <img
                src="{{ asset('imagens/logo.png') }}"
                alt="{{ config('store.name') }}"
                class="h-7 w-auto brightness-0 invert opacity-30"
                onerror="this.src='{{ asset('imagens/logo.svg') }}';"
            >

            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5 text-xs">
                <span>{{ config('store.address') }}</span>
                <span class="text-neutral-700 hidden sm:inline">·</span>
                <a href="tel:+55{{ preg_replace('/\D/', '', config('store.phone')) }}" class="hover:text-neutral-300 transition-colors">
                    {{ config('store.phone') }}
                </a>
                <span class="text-neutral-700 hidden sm:inline">·</span>
                <a
                    href="https://wa.me/{{ config('store.whatsapp_number') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hover:text-[#25D366] transition-colors"
                >
                    WhatsApp
                </a>
            </div>

            <p class="text-[10px] text-neutral-700">
                &copy; {{ date('Y') }} {{ config('store.name') }}. Todos os direitos reservados.
            </p>
        </div>
    </div>
</footer>
