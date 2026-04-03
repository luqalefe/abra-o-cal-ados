<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Services\WhatsAppUrlGenerator;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ShowCatalog extends Component
{
    public ?int $selectedCategory = null;

    public function filterByCategory(?int $categoryId): void
    {
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

        $products = Cache::remember("promoted_products_{$categoryKey}", 300, function () {
            return Product::promoted()
                ->with('category')
                ->when($this->selectedCategory, fn ($query) => $query->where('category_id', $this->selectedCategory))
                ->latest()
                ->get();
        });

        // Pre-compute WhatsApp URLs to avoid calling methods with parameters in Blade
        $products->each(function (Product $product) {
            $product->whatsapp_url = WhatsAppUrlGenerator::generateFromProduct($product);
        });

        return view('livewire.show-catalog', [
            'categories' => Cache::remember('active_categories', 3600, fn () => Category::active()->get()),
            'products' => $products,
        ]);
    }
}
