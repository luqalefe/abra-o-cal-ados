<?php

use App\Models\Category;
use App\Models\Product;

it('belongs to a category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->category->id)->toBe($category->id);
});

it('has promoted scope', function () {
    Product::factory()->create(['is_promoted' => true]);
    Product::factory()->create(['is_promoted' => false]);

    expect(Product::promoted()->count())->toBe(1);
});

it('formats price as BRL currency', function () {
    $product = Product::factory()->make(['price' => 1250.50]);

    expect($product->formatted_price)->toBe('R$ 1.250,50');
});
