{{-- Category Filter Component --}}
@props(['categories', 'selectedCategory'])

<div class="sticky top-0 z-20 glass shadow-sm border-b border-stone-100/50">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-0.5">
            {{-- "Todos" button --}}
            <button 
                wire:click="selectCategory(null)"
                class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 transform
                    {{ $selectedCategory === null 
                        ? 'bg-neutral-900 text-brand-400 shadow-lg shadow-neutral-900/25 scale-105' 
                        : 'bg-stone-100 text-stone-600 hover:bg-stone-200 hover:text-stone-800 hover:scale-[1.02]' }}"
            >
                Todos
            </button>

            {{-- Category buttons --}}
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})"
                    class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 transform
                        {{ $selectedCategory == $category->id 
                            ? 'bg-neutral-900 text-brand-400 shadow-lg shadow-neutral-900/25 scale-105' 
                            : 'bg-stone-100 text-stone-600 hover:bg-stone-200 hover:text-stone-800 hover:scale-[1.02]' }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>
