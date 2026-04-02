<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome-catalog');
})->name('catalogo');

Route::get('/produto/{product}', function (Product $product) {
    return view('product-detail', compact('product'));
})->name('produto.show');

// SEO: Dynamic Sitemap
Route::get('/sitemap.xml', function () {
    $products = Product::with('category')->latest()->get();
    
    $content = view('seo.sitemap', compact('products'))->render();
    
    return response($content, 200)
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// SEO: Robots.txt (dynamic)
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin\n";
    $content .= "Disallow: /admin/*\n\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
    
    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});
