{{-- Empty State Component --}}
@props(['message' => 'Nenhum produto em promoção nesta categoria no momento.'])

<div class="col-span-full py-20 text-center animate-fade-in">
    <div class="inline-flex flex-col items-center gap-4">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <p class="text-gray-400 text-sm font-medium max-w-xs">{{ $message }}</p>
    </div>
</div>
