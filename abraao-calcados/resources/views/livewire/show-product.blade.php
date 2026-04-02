{{-- ShowProduct Livewire View --}}

<div class="min-h-screen bg-stone-50">
    {{-- Breadcrumb / Back navigation --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <a href="/" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-700 transition-colors group" wire:navigate>
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar ao catálogo
            </a>
        </div>
    </div>

    {{-- Product Detail --}}
    <main class="max-w-7xl mx-auto px-4 py-6 md:py-10">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            {{-- Image Section --}}
            <div class="lg:w-1/2" x-data='{ 
                activeImage: 0,
                images: @json(collect($product->images ?? [])->map(fn($img) => Storage::url($img)))
            }'>
                <div class="sticky top-4">
                    {{-- Main Image Display --}}
                    <div class="aspect-square bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative group">
                        <template x-if="images.length > 0">
                            <img 
                                :src="images[activeImage]" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover transition-all duration-500"
                                :class="{ 'scale-105': true }"
                            >
                        </template>
                        <template x-if="images.length === 0">
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>

                        {{-- Promo badge --}}
                        @if($product->is_promoted)
                            <div class="absolute top-4 left-4">
                                <span class="inline-flex items-center gap-1 bg-brand-700 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Oferta
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    <div class="mt-4 grid grid-cols-4 md:grid-cols-5 gap-3" x-show="images.length > 1">
                        <template x-for="(image, index) in images" :key="index">
                            <button 
                                @click="activeImage = index"
                                class="aspect-square rounded-xl border-2 overflow-hidden transition-all duration-200 bg-white"
                                :class="activeImage === index ? 'border-brand-700 ring-2 ring-brand-700/20' : 'border-gray-100 hover:border-brand-300'"
                            >
                                <img :src="image" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Info Section --}}
            <div class="lg:w-1/2 flex flex-col">
                {{-- Category --}}
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest mb-2">
                    {{ $product->category->name }}
                </span>

                {{-- Product Name --}}
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight mb-4">
                    {{ $product->name }}
                </h1>

                {{-- Price Section --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                    {{-- Main price --}}
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-3xl md:text-4xl font-black text-brand-700">
                            {{ $product->formatted_price }}
                        </span>
                    </div>

                    {{-- Payment methods --}}
                    <div class="space-y-3">
                        {{-- PIX --}}
                        <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-xl p-3.5">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.253 7.466l-3.39 3.39a1.217 1.217 0 01-1.726 0l-3.39-3.39a2.78 2.78 0 00-1.96-.813H5.08l4.497 4.497a2.973 2.973 0 004.21 0l4.498-4.497H16.8a2.78 2.78 0 00-1.547.813z"/>
                                    <path d="M17.253 16.534l-3.39-3.39a1.217 1.217 0 00-1.726 0l-3.39 3.39a2.78 2.78 0 01-1.96.813H5.08l4.497-4.497a2.973 2.973 0 014.21 0l4.498 4.497H16.8a2.78 2.78 0 01-1.547-.813z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-green-700">PIX</span>
                                    <span class="text-[10px] font-bold text-white bg-green-500 px-1.5 py-0.5 rounded-full uppercase">5% off</span>
                                </div>
                                <span class="text-lg font-black text-green-700">R$ {{ $pixPrice }}</span>
                            </div>
                        </div>

                        {{-- Cartão de Crédito --}}
                        <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-xl p-3.5">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-bold text-amber-700">Cartão de Crédito</span>
                                <div class="text-sm text-amber-600">
                                    <span class="font-bold">3x</span> de <span class="font-black text-amber-700">R$ {{ $installmentPrice }}</span> <span class="text-xs text-amber-500">sem juros</span>
                                </div>
                            </div>
                        </div>

                        {{-- Cartão de Débito --}}
                        <div class="flex items-center gap-3 bg-stone-50 border border-stone-200 rounded-xl p-3.5">
                            <div class="w-10 h-10 bg-stone-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-bold text-stone-700">Cartão de Débito</span>
                                <div class="text-sm text-stone-600">
                                    <span class="font-black text-stone-800">{{ $product->formatted_price }}</span> <span class="text-xs text-stone-500">à vista</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($product->description)
                    <div class="mb-6">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Descrição</h2>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>
                @endif

                {{-- WhatsApp CTA --}}
                <div class="mt-auto space-y-3">
                    <a 
                        href="{{ $whatsappUrl }}" 
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group/btn flex items-center justify-center gap-3 w-full bg-[#25D366] hover:bg-[#1EBE5D] active:bg-[#1AA84F] text-white py-4 px-6 rounded-2xl font-bold text-base transition-all duration-200 shadow-lg shadow-[#25D366]/25 hover:shadow-xl hover:shadow-[#25D366]/30 hover:scale-[1.01] active:scale-[0.99]"
                    >
                        <svg class="w-6 h-6 transition-transform group-hover/btn:rotate-12 duration-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <span>Comprar pelo WhatsApp</span>
                    </a>

                    <p class="text-center text-[11px] text-gray-400">
                        Você será redirecionado para o WhatsApp da loja
                    </p>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->count() > 0)
            <section class="mt-12 md:mt-16">
                <h2 class="text-lg font-bold text-gray-800 mb-5">
                    Mais em {{ $product->category->name }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedProducts as $related)
                        <x-catalog.product-card :product="$related" />
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    {{-- Footer --}}
    <x-catalog.footer />
</div>
