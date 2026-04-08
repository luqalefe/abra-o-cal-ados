{!! '<' . '?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Product pages (all products, not just promoted) --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('produto.show', $product) }}</loc>
        <lastmod>{{ $product->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($product->images && count($product->images) > 0)
        <image:image>
            <image:loc>{{ url(Storage::url($product->images[0])) }}</image:loc>
            <image:title>{{ $product->display_name }} - Abraão Calçados Rio Branco</image:title>
            <image:caption>{{ $product->display_name }}{{ $product->category ? ' - ' . $product->category->name : '' }} disponível na Abraão Calçados em Rio Branco, Acre</image:caption>
        </image:image>
        @endif
    </url>
    @endforeach

</urlset>
