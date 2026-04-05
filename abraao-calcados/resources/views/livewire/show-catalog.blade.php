{{-- ShowCatalog Livewire View --}}

@php $lcpImage = $products->first()?->images[0] ?? null; @endphp
@if($lcpImage)
    @push('meta')
        <link rel="preload" as="image" href="{{ Storage::url($lcpImage) }}" fetchpriority="high">
    @endpush
@endif

<div class="min-h-screen bg-stone-50">

    <x-catalog.header />

    <x-catalog.category-filter
        :categories="$categories"
        :selected-category="$selectedCategory"
    />

    <main class="max-w-7xl mx-auto px-4 py-6 md:py-10">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @forelse($products as $product)
                <x-catalog.product-card :product="$product" :index="$loop->index" />
            @empty
                <x-catalog.empty-state />
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </main>

    <x-catalog.location />

    <x-catalog.footer />

</div>
