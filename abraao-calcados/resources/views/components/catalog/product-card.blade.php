{{-- Product Card Component --}}
@props(['product', 'index' => 99, 'isFirst' => false])

<div class="group bg-white rounded-xl overflow-hidden flex flex-col border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all duration-200 animate-fade-in">

    {{-- Image --}}
    <a href="{{ route('produto.show', $product) }}" class="relative block aspect-square bg-gray-50 overflow-hidden">
        @if($product->images && count($product->images) > 0)
            <img
                src="{{ Storage::url($product->images[0]) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                @if($index === 0) fetchpriority="high" @elseif($index >= 4) loading="lazy" @endif
                width="400"
                height="400"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        <span class="absolute top-2 left-2 bg-brand-700 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wide">
            Oferta
        </span>
    </a>

    {{-- Info --}}
    <div class="p-3 md:p-4 flex flex-col flex-grow gap-2">

        {{-- Categoria + Nome --}}
        <div>
            <span class="text-[10px] font-semibold text-brand-700 uppercase tracking-wider">
                {{ $product->category->name }}
            </span>
            <a href="{{ route('produto.show', $product) }}" class="block mt-0.5">
                <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug hover:text-brand-700 transition-colors">
                    {{ $product->name }}
                </h3>
            </a>
        </div>

        {{-- Preço + CTA --}}
        <div class="mt-auto space-y-2">
            <div>
                <p class="text-lg font-black text-gray-900 leading-none">
                    {{ $product->formatted_price }}
                </p>
                <p class="text-xs text-green-600 font-medium mt-0.5">
                    R$ {{ number_format($product->price * 0.95, 2, ',', '.') }} no PIX
                </p>
            </div>

            <x-catalog.whatsapp-button :url="$product->whatsapp_url" />
        </div>

    </div>
</div>
