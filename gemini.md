# Abraão Calçados — Guia de Desenvolvimento TDD

## Identidade do Projeto

Este é um **catálogo digital** (estilo "link na bio") para a loja Abraão Calçados. O objetivo é exibir produtos em promoção da semana. Não há checkout — a conversão ocorre 100% via WhatsApp.

## Stack Tecnológica

- **Backend:** Laravel 13
- **Admin Panel:** Filament PHP v5
- **Frontend Público:** Livewire v3 + Tailwind CSS + Alpine.js
- **Banco de Dados:** MySQL / SQLite
- **Ambiente Local:** Docker via Laravel Sail
- **Testes:** Pest PHP 4

---

## Regras de Desenvolvimento — TDD Obrigatório

### Princípio Fundamental: Red → Green → Refactor

> **NENHUM código de produção deve ser escrito sem um teste que o justifique.**

Todo desenvolvimento DEVE seguir o ciclo TDD rigorosamente:

1. **🔴 RED** — Escreva o teste PRIMEIRO. O teste DEVE falhar.
2. **🟢 GREEN** — Escreva o MÍNIMO de código necessário para o teste passar.
3. **🔵 REFACTOR** — Melhore o código mantendo todos os testes verdes.

### Ordem de Implementação

Para cada funcionalidade, siga esta ordem:

```
1. Escrever o teste (test file)
2. Rodar o teste → DEVE FALHAR (Red)
3. Criar a migration/model/controller/component necessário
4. Implementar o mínimo de lógica
5. Rodar o teste → DEVE PASSAR (Green)
6. Refatorar mantendo testes verdes (Refactor)
7. Commitar
```

### Regras Anti-Hallucination

> **NUNCA** assuma que um teste passa sem rodar `sail artisan test`.
> **NUNCA** escreva testes que testem implementações internas do framework.
> **SEMPRE** teste comportamento visível ao usuário, não detalhes de implementação.

---

## Estrutura de Testes

### Diretórios

```
tests/
├── Pest.php                            # Config global
├── TestCase.php                        # Base
├── Unit/
│   ├── Models/
│   │   ├── CategoryTest.php
│   │   └── ProductTest.php
│   └── Services/
│       └── WhatsAppUrlGeneratorTest.php
├── Feature/
│   ├── Admin/
│   │   ├── CategoryResourceTest.php
│   │   └── ProductResourceTest.php
│   ├── Livewire/
│   │   └── ShowCatalogTest.php
│   └── Http/
│       └── CatalogPageTest.php
```

### Configuração Global (`tests/Pest.php`)

```php
<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

uses(
    Tests\TestCase::class,
)->in('Unit');
```

---

## Padrões de Teste por Camada

### 1. Testes Unitários (Models & Services)

**O que testar:**
- Relacionamentos do Eloquent (`hasMany`, `belongsTo`)
- Scopes (`scopeActive`, `scopePromoted`)
- Accessors e Mutators (`formatted_price`, auto-slug)
- Services puros (`WhatsAppUrlGenerator`)

**Padrão:**

```php
// tests/Unit/Models/CategoryTest.php
it('has many products', function () {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);

    expect($category->products)->toHaveCount(3);
});

it('generates slug automatically on creation', function () {
    $category = Category::factory()->create(['name' => 'Tênis Esportivo']);

    expect($category->slug)->toBe('tenis-esportivo');
});

it('has active scope that filters inactive categories', function () {
    Category::factory()->create(['is_active' => true]);
    Category::factory()->create(['is_active' => false]);

    expect(Category::active()->count())->toBe(1);
});
```

**NÃO testar:**
- Internals do Eloquent (como o Laravel resolve queries)
- Validação no nível do Model (isso é responsabilidade do Form/Request)

---

### 2. Testes de Feature — Admin Filament

**O que testar:**
- Renderização das páginas (List, Create, Edit)
- CRUD completo (criar, ler, editar, deletar)
- Validação de formulários
- Funcionalidades específicas (auto-slug, toggle promoção, upload imagem)
- Autorização (acesso apenas autenticado)

**Padrão:**

```php
// tests/Feature/Admin/CategoryResourceTest.php
use function Pest\Livewire\livewire;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('can render the list page', function () {
    livewire(ListCategories::class)->assertSuccessful();
});

it('can create a category with auto-generated slug', function () {
    livewire(CreateCategory::class)
        ->fillForm(['name' => 'Chinelos de Praia'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('categories', [
        'name' => 'Chinelos de Praia',
        'slug' => 'chinelos-de-praia',
    ]);
});

it('can toggle is_promoted from the table', function () {
    $product = Product::factory()->create(['is_promoted' => false]);

    livewire(ListProducts::class)
        ->assertCanRenderTableColumn('is_promoted');
});

it('validates required product fields', function () {
    livewire(CreateProduct::class)
        ->fillForm(['name' => '', 'price' => null, 'category_id' => null])
        ->call('create')
        ->assertHasFormErrors(['name', 'price', 'category_id']);
});
```

**Helpers do Filament disponíveis:**
- `assertCanSeeTableRecords($records)` — verifica registros na tabela
- `assertCanNotSeeTableRecords($records)` — verifica ausência
- `assertCountTableRecords($count)` — conta registros
- `assertCanRenderTableColumn($column)` — verifica coluna renderiza
- `fillForm($data)` — preenche formulário
- `assertHasFormErrors($fields)` — verifica erros de validação
- `assertHasNoFormErrors()` — sem erros de validação
- `callAction(ActionClass::class)` — chama ação (delete, etc.)

---

### 3. Testes de Feature — Livewire (Frontend Público)

**O que testar:**
- Componente renderiza com status 200
- Apenas produtos promovidos são exibidos
- Filtro por categoria funciona
- Preço formatado aparece corretamente
- Link do WhatsApp está presente e correto
- Apenas categorias ativas aparecm nos filtros

**Padrão:**

```php
// tests/Feature/Livewire/ShowCatalogTest.php
use Livewire\Livewire;

it('renders the catalog component', function () {
    Livewire::test(ShowCatalog::class)->assertStatus(200);
});

it('only shows promoted products', function () {
    $promoted = Product::factory()->create(['is_promoted' => true]);
    $notPromoted = Product::factory()->create(['is_promoted' => false]);

    Livewire::test(ShowCatalog::class)
        ->assertSee($promoted->name)
        ->assertDontSee($notPromoted->name);
});

it('filters products by category', function () {
    $cat = Category::factory()->create();
    $other = Category::factory()->create();
    $p1 = Product::factory()->create([
        'category_id' => $cat->id, 
        'is_promoted' => true,
    ]);
    $p2 = Product::factory()->create([
        'category_id' => $other->id, 
        'is_promoted' => true,
    ]);

    Livewire::test(ShowCatalog::class)
        ->call('filterByCategory', $cat->id)
        ->assertSee($p1->name)
        ->assertDontSee($p2->name);
});

it('generates correct whatsapp url', function () {
    config(['store.whatsapp_number' => '5511999999999']);
    
    Product::factory()->create([
        'is_promoted' => true,
        'name' => 'Tênis Test',
        'price' => 299.90,
    ]);

    Livewire::test(ShowCatalog::class)
        ->assertSeeHtml('https://wa.me/5511999999999');
});
```

---

### 4. Testes de Rota HTTP

```php
// tests/Feature/Http/CatalogPageTest.php
it('returns 200 on homepage', function () {
    $this->get('/')->assertStatus(200);
});

it('has SEO meta tags', function () {
    $this->get('/')
        ->assertSee('<title>', false)
        ->assertSee('<meta name="description"', false);
});
```

---

## Comandos de Teste

```bash
# Rodar TODOS os testes
./vendor/bin/sail artisan test

# Rodar testes em paralelo (mais rápido)
./vendor/bin/sail artisan test --parallel

# Rodar apenas Unit tests
./vendor/bin/sail artisan test --testsuite=Unit

# Rodar apenas Feature tests
./vendor/bin/sail artisan test --testsuite=Feature

# Rodar um arquivo específico
./vendor/bin/sail artisan test tests/Feature/Livewire/ShowCatalogTest.php

# Rodar um teste específico pelo nome
./vendor/bin/sail artisan test --filter="only shows promoted products"

# Com cobertura de código
./vendor/bin/sail artisan test --coverage --min=80
```

---

## Convenções de Código

### Nomenclatura de Testes

- Use `it()` para descrever comportamentos: `it('can create a category')`
- Use inglês nos testes para consistência com o framework
- Seja descritivo: `it('only shows promoted products on the catalog page')`

### Factories

**Sempre** use factories para criar dados de teste:

```php
// database/factories/CategoryFactory.php
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'slug' => fn (array $attrs) => Str::slug($attrs['name']),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}

// database/factories/ProductFactory.php
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 29.90, 599.90),
            'images' => null, // Array de caminhos
            'is_promoted' => false,
        ];
    }

    public function promoted(): static
    {
        return $this->state(fn () => ['is_promoted' => true]);
    }
}
```

### Configuração da Loja

Todos os dados da loja vêm de `config/store.php`, que lê do `.env`:

```php
config('store.name');              // Nome da loja
config('store.address');           // Endereço
config('store.description');       // Descrição curta
config('store.whatsapp_number');   // Número WhatsApp (formato: 5511999999999)
```

---

## Modelo de Dados

```
┌──────────────┐       ┌──────────────────┐
│  categories  │       │    products       │
├──────────────┤       ├──────────────────┤
│ id           │──┐    │ id               │
│ name         │  │    │ category_id (FK) │──┘
│ slug (unique)│  │    │ name             │
│ is_active    │  └───>│ description      │
│ timestamps   │       │ price (10,2)     │
└──────────────┘       │ images (json)    │
                       │ is_promoted      │
                       │ timestamps       │
                       └──────────────────┘
```

## Regras de Negócio

1. **Promoções:** Apenas produtos com `is_promoted = true` aparecem no catálogo público
2. **Categorias:** Apenas categorias com `is_active = true` aparecem como filtro
3. **WhatsApp:** Mensagem no formato: `"Olá! Gostaria de saber mais sobre o produto [NOME] no valor de R$ [PREÇO]."`
4. **Preço:** Sempre formatado como BRL: `R$ 199,90`
5. **Slug:** Gerado automaticamente a partir do `name` da categoria

---

## Git Workflow com TDD

```bash
# Para cada feature:
git checkout -b feature/nome-da-feature

# 1. Escrever teste → commit
git add tests/ && git commit -m "test: add tests for [feature]"

# 2. Implementar código (GREEN) → commit
git add . && git commit -m "feat: implement [feature]"

# 3. Refatorar → commit
git add . && git commit -m "refactor: clean up [feature]"

# 4. Merge
git checkout main && git merge feature/nome-da-feature
```

## Comandos de Setup (Laravel Sail)

```bash
# 1. Instalar dependências
composer install

# 2. Copiar ambiente
cp .env.example .env

# 3. Subir containers
./vendor/bin/sail up -d

# 4. Gerar chave
./vendor/bin/sail artisan key:generate

# 5. Rodar migrations
./vendor/bin/sail artisan migrate

# 6. Criar link do storage
./vendor/bin/sail artisan storage:link

# 7. Criar usuário admin do Filament
./vendor/bin/sail artisan make:filament-user

# 8. Rodar testes
./vendor/bin/sail artisan test
```
