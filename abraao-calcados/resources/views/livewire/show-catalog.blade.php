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

    {{-- Texto descritivo: aumenta contagem de palavras e relação texto/HTML para SEO --}}
    <section class="bg-white border-t border-stone-100">
        <div class="max-w-7xl mx-auto px-4 py-10 md:py-12">
            <div class="grid md:grid-cols-3 gap-8 text-sm text-stone-500 leading-relaxed">
                <div>
                    <h2 class="font-bold text-stone-700 mb-2">Loja de Calçados em Rio Branco</h2>
                    <p>A Abraão Calçados é uma loja especializada em calçados femininos, masculinos e infantis localizada em Rio Branco, Acre. Trabalhamos com as melhores marcas do mercado e oferecemos os menores preços da região. Nosso catálogo inclui tênis esportivos, sandálias, sapatos sociais, chinelos, botas e muito mais.</p>
                </div>
                <div>
                    <h2 class="font-bold text-stone-700 mb-2">Como Comprar pelo WhatsApp</h2>
                    <p>É simples e rápido. Escolha o produto que deseja no nosso catálogo online, clique no botão do WhatsApp e fale diretamente com um de nossos atendentes. Aceitamos cartão de crédito, débito e PIX. Compras com PIX têm desconto de 5%. Também trabalhamos com parcelamento em até 3 vezes sem juros.</p>
                </div>
                <div>
                    <h2 class="font-bold text-stone-700 mb-2">Visite Nossa Loja</h2>
                    <p>Estamos localizados na Estr. Juarez Távora, 206, no bairro Alto Alegre, em Rio Branco, Acre. Funcionamos de segunda a sábado, das 8h às 18h. Venha nos visitar e aproveitar as promoções semanais em calçados de qualidade. Temos novidades sempre chegando para toda a família.</p>
                </div>
            </div>
        </div>
    </section>

    <x-catalog.footer />

</div>
