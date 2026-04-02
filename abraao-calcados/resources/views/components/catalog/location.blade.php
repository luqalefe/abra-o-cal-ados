{{-- Location / Como Chegar Component --}}
{{-- Shows a "How to get here" section with embedded map and directions button --}}

<section class="bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Section title --}}
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h2 class="text-lg font-bold text-gray-800">Como chegar</h2>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
            {{-- Embedded Map — Estr. Juarez Távora, 206, Alto Alegre, Rio Branco - AC --}}
            <div class="w-full md:w-2/3 rounded-xl overflow-hidden shadow-sm border border-gray-100" style="min-height: 220px;">
                <iframe 
                    src="https://maps.google.com/maps?q=Estrada+Juarez+T%C3%A1vora,+206,+Alto+Alegre,+Rio+Branco,+AC,+69921-248&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    width="100%" 
                    height="220" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Localização Abraão Calçados - {{ config('store.address') }}"
                    class="w-full"
                ></iframe>
            </div>

            {{-- Info + Directions button --}}
            <div class="w-full md:w-1/3 flex flex-col justify-between gap-4">
                {{-- Address card --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-700/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ config('store.name') }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ config('store.address') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Rio Branco — AC</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-700/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <a href="tel:+55{{ preg_replace('/\D/', '', config('store.phone')) }}" class="text-sm text-gray-700 hover:text-brand-700 transition-colors font-medium">
                            {{ config('store.phone') }}
                        </a>
                    </div>

                    {{-- Horário de funcionamento --}}
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-700/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-xs text-gray-500">
                            <p class="font-medium text-gray-700">Seg–Sáb: 8h às 18h</p>
                        </div>
                    </div>
                </div>

                {{-- Directions button — opens Google Maps with user's current location as origin --}}
                <a 
                    href="https://www.google.com/maps/dir/?api=1&destination=Estr.+Juarez+Tavora,+206+-+Alto+Alegre,+Rio+Branco+-+AC&travelmode=driving" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-center gap-2 w-full bg-brand-700 hover:bg-brand-800 active:bg-brand-900 text-white py-3 px-4 rounded-xl font-bold text-sm transition-all duration-200 shadow-md shadow-brand-700/20 hover:shadow-lg hover:shadow-brand-700/30 hover:scale-[1.01] active:scale-[0.99]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Como chegar
                </a>
            </div>
        </div>
    </div>
</section>
