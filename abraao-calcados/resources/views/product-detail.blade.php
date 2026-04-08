@php
    $seoTitle = $product->display_name . ' | Abraão Calçados Rio Branco - AC | R$ ' . number_format($product->price, 2, ',', '.');
    $categoryName = $product->category?->name ?? 'Calçados';
    $seoDesc = 'Compre ' . $product->display_name . ' por ' . $product->formatted_price . ' na Abraão Calçados em Rio Branco, Acre. ' . ($product->description ?? $categoryName . ' com os melhores preços.') . ' Pague no PIX com 5% de desconto ou em 3x no cartão.';
    $seoKeywords = strtolower($product->display_name) . ', ' . strtolower($categoryName) . ' rio branco, comprar ' . strtolower($categoryName) . ' rio branco acre, calçados rio branco';
    $ogImg = ($product->images && count($product->images) > 0) ? url(Storage::url($product->images[0])) : '';

    // JSON-LD Product via PHP (evita conflito @type/@context)
    $productJsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->display_name,
        'description' => $product->description ?? $product->display_name . ' - ' . $categoryName . ' disponível na Abraão Calçados em Rio Branco, Acre.',
        'image' => $ogImg ?: null,
        'category' => $categoryName,
        'brand' => ['@type' => 'Brand', 'name' => 'Abraão Calçados'],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('produto.show', $product),
            'priceCurrency' => 'BRL',
            'price' => $product->price,
            'priceValidUntil' => now()->addMonths(3)->format('Y-m-d'),
            'availability' => 'https://schema.org/InStock',
            'seller' => ['@type' => 'Organization', 'name' => 'Abraão Calçados'],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $breadcrumbJsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Catálogo', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $categoryName, 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $product->display_name, 'item' => route('produto.show', $product)],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp

<x-layouts.catalog
    :title="$seoTitle"
    :description="$seoDesc"
    :keywords="$seoKeywords"
    :canonical="route('produto.show', $product)"
    :og-title="$product->display_name . ' - ' . $product->formatted_price . ' | Abraão Calçados'"
    :og-description="$product->display_name . ' por ' . $product->formatted_price . '. ' . $categoryName . ' na Abraão Calçados, Rio Branco - AC.'"
    og-type="product"
    :og-image="$ogImg"
>
    {{-- Product & Breadcrumb JSON-LD --}}
    <script type="application/ld+json">{!! $productJsonLd !!}</script>
    <script type="application/ld+json">{!! $breadcrumbJsonLd !!}</script>

    <livewire:show-product :product="$product" />
</x-layouts.catalog>
