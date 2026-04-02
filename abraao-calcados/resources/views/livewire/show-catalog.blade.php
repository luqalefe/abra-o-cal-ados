{{-- ShowCatalog Livewire View --}}
{{-- Composes reusable components: header, category-filter, product-card, empty-state, footer --}}

<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <x-catalog.header />

    {{-- Category Filter Bar --}}
    <x-catalog.category-filter 
        :categories="$categories" 
        :selected-category="$selectedCategory" 
    />

    {{-- Product Grid --}}
    <main class="max-w-7xl mx-auto py-8 px-4">
        {{-- Results count --}}
        @if($products->count() > 0)
            <p class="text-xs text-gray-400 mb-6 font-medium uppercase tracking-wider">
                {{ $products->count() }} {{ $products->count() === 1 ? 'produto encontrado' : 'produtos encontrados' }}
            </p>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @forelse($products as $product)
                <x-catalog.product-card :product="$product" />
            @empty
                <x-catalog.empty-state />
            @endforelse
        </div>
    </main>

    {{-- Como chegar (location + directions) --}}
    <x-catalog.location />

    {{-- Footer --}}
    <x-catalog.footer />
</div>
