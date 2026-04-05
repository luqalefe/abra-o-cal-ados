{{-- Location / Como Chegar Component --}}
<section class="bg-white border-t border-stone-100 mt-4">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Como chegar
        </h2>

        <div class="flex flex-col md:flex-row gap-4">
            {{-- Map --}}
            <div class="w-full md:w-2/3 rounded-xl overflow-hidden border border-stone-100" style="min-height: 200px;">
                <iframe
                    src="https://maps.google.com/maps?q=Estrada+Juarez+T%C3%A1vora,+206,+Alto+Alegre,+Rio+Branco,+AC,+69921-248&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    width="100%"
                    height="200"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Localização {{ config('store.name') }}"
                    class="w-full"
                ></iframe>
            </div>

            {{-- Info --}}
            <div class="w-full md:w-1/3 flex flex-col gap-3">
                <div class="bg-stone-50 rounded-xl p-4 space-y-3 flex-1">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ config('store.name') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ config('store.address') }}</p>
                    </div>

                    <div class="space-y-1.5 text-xs text-gray-500">
                        <p>
                            <a href="tel:+55{{ preg_replace('/\D/', '', config('store.phone')) }}" class="hover:text-brand-700 transition-colors font-medium text-gray-700">
                                {{ config('store.phone') }}
                            </a>
                        </p>
                        <p class="text-gray-400">Seg–Sáb: 8h às 18h</p>
                    </div>
                </div>

                <a
                    href="https://www.google.com/maps/dir/?api=1&destination=Estr.+Juarez+Tavora,+206+-+Alto+Alegre,+Rio+Branco+-+AC&travelmode=driving"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-center gap-2 w-full bg-brand-700 hover:bg-brand-800 active:bg-brand-900 text-white py-3 px-4 rounded-xl font-bold text-sm transition-colors shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Traçar rota
                </a>
            </div>
        </div>
    </div>
</section>
