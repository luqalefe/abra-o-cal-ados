{{-- ShowProduct Livewire View --}}

<div class="min-h-screen bg-stone-50">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <a href="/" class="inline-flex items-center gap-1.5 text-sm text-stone-400 hover:text-brand-700 transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar ao catálogo
            </a>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-6 md:py-10">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            {{-- Galeria --}}
            <div class="lg:w-1/2" x-data='{
                activeImage: 0,
                images: @json(collect($product->images ?? [])->map(fn($img) => Storage::url($img)))
            }'>
                <div class="sticky top-4 space-y-3">

                    {{-- Imagem principal --}}
                    <div class="aspect-square bg-white rounded-xl border border-gray-100 overflow-hidden relative">
                        <template x-if="images.length > 0">
                            <img
                                :src="images[activeImage]"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                            >
                        </template>
                        <template x-if="images.length === 0">
                            <div class="w-full h-full flex items-center justify-center text-gray-200">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>

                        @if($product->is_promoted)
                            <span class="absolute top-3 left-3 bg-brand-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide">
                                Oferta
                            </span>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    <div class="grid grid-cols-5 gap-2" x-show="images.length > 1">
                        <template x-for="(image, index) in images" :key="index">
                            <button
                                @click="activeImage = index"
                                class="aspect-square rounded-lg border-2 overflow-hidden transition-colors bg-white"
                                :class="activeImage === index ? 'border-brand-700' : 'border-gray-100 hover:border-gray-300'"
                            >
                                <img :src="image" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>

                </div>
            </div>

            {{-- Informações --}}
            <div class="lg:w-1/2 flex flex-col gap-5">

                {{-- Categoria + Nome --}}
                <div>
                    <span class="text-xs font-semibold text-brand-600 uppercase tracking-wider">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="mt-1 text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                {{-- Preços --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5 space-y-4">
                    {{-- Preço principal --}}
                    <div>
                        <p class="text-3xl md:text-4xl font-black text-gray-900">
                            {{ $product->formatted_price }}
                        </p>
                        <p class="text-sm text-stone-400 mt-0.5">no cartão ou débito</p>
                    </div>

                    {{-- Divisor --}}
                    <div class="border-t border-stone-100"></div>

                    {{-- PIX + Parcelas lado a lado --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-green-50 rounded-lg p-3">
                            <p class="text-[10px] font-bold text-green-700 uppercase tracking-wider mb-1">PIX — 5% off</p>
                            <p class="text-lg font-black text-green-700">R$ {{ $pixPrice }}</p>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-3">
                            <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1">3x sem juros</p>
                            <p class="text-lg font-black text-amber-700">R$ {{ $installmentPrice }}</p>
                        </div>
                    </div>
                </div>

                {{-- Descrição --}}
                @if($product->description)
                    <div>
                        <h2 class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-2">Descrição</h2>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- CTA --}}
                <div class="mt-auto space-y-2">
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center justify-center gap-3 w-full bg-[#25D366] hover:bg-[#20c05c] active:bg-[#1aa84f] text-white py-4 px-6 rounded-xl font-bold text-base transition-colors shadow-sm hover:shadow-md hover:shadow-[#25D366]/20"
                    >
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Comprar pelo WhatsApp
                    </a>
                    <p class="text-center text-xs text-stone-400">
                        Você será redirecionado para o WhatsApp da loja
                    </p>
                </div>

            </div>
        </div>

        {{-- Produtos relacionados --}}
        @if($relatedProducts->count() > 0)
            <section class="mt-12 md:mt-16">
                <h2 class="text-base font-bold text-gray-800 mb-4">
                    Mais em {{ $product->category->name }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    @foreach($relatedProducts as $related)
                        <x-catalog.product-card :product="$related" />
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <x-catalog.footer />

</div>
