{{-- Product Card Component --}}
@props(['product'])

<div class="group bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col border border-gray-100 hover:shadow-xl hover:shadow-brand-700/5 hover:-translate-y-1 transition-all duration-300 animate-fade-in">
    {{-- Image container (clickable) --}}
    <a href="{{ route('produto.show', $product) }}" class="aspect-square bg-gradient-to-br from-gray-100 to-gray-50 relative overflow-hidden block">
        @if($product->images && count($product->images) > 0)
            <img 
                src="{{ Storage::url($product->images[0]) }}" 
                alt="{{ $product->name }}" 
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 img-load"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        {{-- Promo badge --}}
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center gap-1 bg-brand-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-widest shadow-lg">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Oferta
            </span>
        </div>
    </a>
    
    {{-- Content --}}
    <div class="p-4 md:p-5 flex flex-col flex-grow">
        {{-- Category tag --}}
        <span class="text-[10px] font-bold text-brand-600 uppercase tracking-widest mb-1.5">
            {{ $product->category->name }}
        </span>

        {{-- Product name (clickable) --}}
        <a href="{{ route('produto.show', $product) }}" class="hover:text-brand-700 transition-colors">
            <h3 class="text-sm md:text-base font-bold text-gray-900 line-clamp-2 mb-3 leading-snug min-h-[2.5rem] group-hover:text-brand-700 transition-colors">
                {{ $product->name }}
            </h3>
        </a>
        
        {{-- Price & CTA --}}
        <div class="mt-auto space-y-2">
            <p class="text-2xl font-black text-brand-700 tracking-tight">
                {{ $product->formatted_price }}
            </p>

            {{-- PIX hint --}}
            <p class="text-[11px] text-green-600 font-semibold">
                R$ {{ number_format($product->price * 0.95, 2, ',', '.') }} no PIX
            </p>
            
            <x-catalog.whatsapp-button :url="$product->whatsapp_url" />

            <a href="{{ route('produto.show', $product) }}" class="block text-center text-xs text-gray-400 hover:text-brand-600 font-medium transition-colors pt-1">
                Ver detalhes →
            </a>
        </div>
    </div>
</div>
