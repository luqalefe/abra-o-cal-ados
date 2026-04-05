{{-- ShowCatalog Livewire View --}}

<div class="min-h-screen bg-stone-50">

    <x-catalog.header />

    <x-catalog.category-filter
        :categories="$categories"
        :selected-category="$selectedCategory"
    />

    <main class="max-w-7xl mx-auto px-4 py-6 md:py-10">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @forelse($products as $product)
                <x-catalog.product-card :product="$product" :isFirst="$loop->first" />
            @empty
                <x-catalog.empty-state />
            @endforelse
        </div>
    </main>

    <x-catalog.location />

    <x-catalog.footer />

</div>
