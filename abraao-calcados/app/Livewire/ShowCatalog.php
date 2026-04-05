<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Services\WhatsAppUrlGenerator;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCatalog extends Component
{
    use WithPagination;

    public ?int $selectedCategory = null;

    public function filterByCategory(?int $categoryId): void
    {
        $this->resetPage();
        $this->selectedCategory = $categoryId;
    }

    /**
     * Alias for filterByCategory — keeps backward compatibility with existing views.
     */
    public function selectCategory(?int $id): void
    {
        $this->filterByCategory($id);
    }

    public function render()
    {
        $categoryKey = $this->selectedCategory ?? 'all';

        $products = Product::promoted()
            ->with('category')
            ->when($this->selectedCategory, fn ($query) => $query->where('category_id', $this->selectedCategory))
            ->latest()
            ->paginate(12);

        // Pre-compute WhatsApp URLs to avoid calling methods with parameters in Blade
        $products->each(function (Product $product) {
            $product->whatsapp_url = WhatsAppUrlGenerator::generateFromProduct($product);
        });

        return view('livewire.show-catalog', [
            'categories' => Category::active()->get(),
            'products' => $products,
        ]);
    }
}
