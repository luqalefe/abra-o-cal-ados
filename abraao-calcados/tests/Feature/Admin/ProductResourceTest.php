<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Filament\Resources\Products\ProductResource;
use Filament\Facades\Filament;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('can render product list page', function () {
    get(ProductResource::getUrl('index'))->assertStatus(200);
});

it('can list products', function () {
    $products = Product::factory()->count(5)->create();

    Livewire::test('App\Filament\Resources\Products\Pages\ListProducts')
        ->assertCanSeeTableRecords($products);
});

it('can create a product', function () {
    $category = Category::factory()->create();
    
    $newData = [
        'category_id' => $category->id,
        'name' => 'Tênis Novo',
        'price' => 199.90,
        'is_promoted' => true,
    ];

    Livewire::test('App\Filament\Resources\Products\Pages\CreateProduct')
        ->fillForm($newData)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('products', [
        'name' => 'Tênis Novo',
        'price' => 199.90,
    ]);
});

it('can toggle product promotion from table', function () {
    $product = Product::factory()->create(['is_promoted' => false]);

    Livewire::test('App\Filament\Resources\Products\Pages\ListProducts')
        ->assertCanSeeTableRecords([$product])
        ->assertCanRenderTableColumn('is_promoted');
});

it('validates required fields', function () {
    Livewire::test('App\Filament\Resources\Products\Pages\CreateProduct')
        ->fillForm([
            'name' => '',
            'price' => null,
            'category_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'price', 'category_id']);
});

it('can edit a product', function () {
    $product = Product::factory()->create(['name' => 'Original']);

    Livewire::test('App\Filament\Resources\Products\Pages\EditProduct', [
        'record' => $product->getRouteKey(),
    ])
        ->fillForm(['name' => 'Editado'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($product->fresh()->name)->toBe('Editado');
});
