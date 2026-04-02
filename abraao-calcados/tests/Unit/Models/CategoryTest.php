<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

it('has many products', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($category->products)->toHaveCount(1)
        ->and($category->products->first()->id)->toBe($product->id);
});

it('generates slug from name automatically', function () {
    $category = Category::create([
        'name' => 'Tênis Esportivos',
    ]);

    expect($category->slug)->toBe('tenis-esportivos');
});

it('has active scope', function () {
    Category::factory()->create(['is_active' => true]);
    Category::factory()->create(['is_active' => false]);

    expect(Category::active()->count())->toBe(1);
});
