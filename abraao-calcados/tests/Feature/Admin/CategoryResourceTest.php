<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use App\Filament\Resources\CategoryResource;
use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('can render category list page', function () {
    get(CategoryResource::getUrl('index'))->assertStatus(200);
});

it('can list categories', function () {
    $categories = Category::factory()->count(10)->create();

    Livewire::test('App\Filament\Resources\Categories\Pages\ListCategories')
        ->assertCanSeeTableRecords($categories);
});

it('can create a category', function () {
    $newData = [
        'name' => 'Novos Calçados',
        'is_active' => true,
    ];

    Livewire::test('App\Filament\Resources\Categories\Pages\CreateCategory')
        ->fillForm($newData)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('categories', [
        'name' => 'Novos Calçados',
        'slug' => 'novos-calcados',
    ]);
});

it('auto-generates slug from name', function () {
    Livewire::test('App\Filament\Resources\Categories\Pages\CreateCategory')
        ->fillForm(['name' => 'Tênis Incríveis'])
        ->assertFormSet(['slug' => 'tenis-incriveis']);
});

it('can edit a category', function () {
    $category = Category::factory()->create(['name' => 'Original']);

    Livewire::test('App\Filament\Resources\Categories\Pages\EditCategory', [
        'record' => $category->getRouteKey(),
    ])
        ->fillForm(['name' => 'Editado'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->fresh()->name)->toBe('Editado');
});

it('can delete a category', function () {
    $category = Category::factory()->create();

    Livewire::test('App\Filament\Resources\Categories\Pages\EditCategory', [
        'record' => $category->getRouteKey(),
    ])
        ->callAction('delete');

    $this->assertModelMissing($category);
});

it('validates name is required', function () {
    Livewire::test('App\Filament\Resources\Categories\Pages\CreateCategory')
        ->fillForm(['name' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});
