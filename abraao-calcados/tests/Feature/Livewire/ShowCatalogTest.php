<?php

use App\Livewire\ShowCatalog;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

it('renders show catalog component', function () {
    Livewire::test(ShowCatalog::class)
        ->assertStatus(200);
});

it('only shows promoted products', function () {
    Product::factory()->create(['is_promoted' => true, 'name' => 'Produto Promovido']);
    Product::factory()->create(['is_promoted' => false, 'name' => 'Produto Normal']);

    Livewire::test(ShowCatalog::class)
        ->assertSee('Produto Promovido')
        ->assertDontSee('Produto Normal');
});

it('filters products by category', function () {
    $categoryA = Category::factory()->create(['name' => 'Categoria A']);
    $categoryB = Category::factory()->create(['name' => 'Categoria B']);

    Product::factory()->create(['category_id' => $categoryA->id, 'is_promoted' => true, 'name' => 'Produto A']);
    Product::factory()->create(['category_id' => $categoryB->id, 'is_promoted' => true, 'name' => 'Produto B']);

    Livewire::test(ShowCatalog::class)
        ->call('selectCategory', $categoryA->id)
        ->assertSee('Produto A')
        ->assertDontSee('Produto B');
});

it('shows all promoted products when no category filter', function () {
    Product::factory()->create(['is_promoted' => true, 'name' => 'Produto X']);
    Product::factory()->create(['is_promoted' => true, 'name' => 'Produto Y']);

    Livewire::test(ShowCatalog::class)
        ->call('selectCategory', null)
        ->assertSee('Produto X')
        ->assertSee('Produto Y');
});

it('displays only active categories as filter buttons', function () {
    Category::factory()->create(['name' => 'Ativa', 'is_active' => true]);
    Category::factory()->create(['name' => 'Inativa', 'is_active' => false]);

    Livewire::test(ShowCatalog::class)
        ->assertSee('Ativa')
        ->assertDontSee('Inativa');
});

it('displays formatted price on product card', function () {
    Product::factory()->create([
        'is_promoted' => true,
        'price' => 199.90,
    ]);

    Livewire::test(ShowCatalog::class)
        ->assertSeeHtml('199,90');
});

it('generates correct whatsapp link', function () {
    config(['store.whatsapp_number' => '5511999999999']);

    Product::factory()->create([
        'is_promoted' => true,
        'name' => 'Tênis Test',
        'price' => 299.90,
    ]);

    Livewire::test(ShowCatalog::class)
        ->assertSeeHtml('https://wa.me/5511999999999');
});

it('filters using filterByCategory method', function () {
    $cat = Category::factory()->create(['name' => 'Botas']);
    Product::factory()->create(['category_id' => $cat->id, 'is_promoted' => true, 'name' => 'Bota Couro']);
    Product::factory()->create(['is_promoted' => true, 'name' => 'Outro Produto']);

    Livewire::test(ShowCatalog::class)
        ->call('filterByCategory', $cat->id)
        ->assertSee('Bota Couro')
        ->assertDontSee('Outro Produto');
});
