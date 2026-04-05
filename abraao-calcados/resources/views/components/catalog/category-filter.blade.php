{{-- Category Filter Component --}}
@props(['categories', 'selectedCategory'])

<div class="sticky top-0 z-20 bg-white border-b border-stone-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-2 sm:px-4">
        <div class="flex items-stretch overflow-x-auto scrollbar-hide">

            {{-- Todos --}}
            <button
                wire:click="selectCategory(null)"
                class="flex-shrink-0 px-4 sm:px-5 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors duration-150
                    {{ $selectedCategory === null
                        ? 'border-brand-700 text-brand-700'
                        : 'border-transparent text-stone-500 hover:text-stone-800 hover:border-stone-300' }}"
            >
                Todos
            </button>

            @foreach($categories as $category)
                <button
                    wire:click="selectCategory({{ $category->id }})"
                    class="flex-shrink-0 px-4 sm:px-5 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors duration-150
                        {{ $selectedCategory == $category->id
                            ? 'border-brand-700 text-brand-700'
                            : 'border-transparent text-stone-500 hover:text-stone-800 hover:border-stone-300' }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach

        </div>
    </div>
</div>
