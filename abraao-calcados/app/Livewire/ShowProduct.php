<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\WhatsAppUrlGenerator;
use Livewire\Component;

class ShowProduct extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product->load('category');
    }

    public function render()
    {
        $relatedProducts = Product::promoted()
            ->available()
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->limit(4)
            ->get();

        $relatedProducts->each(function (Product $p) {
            $p->whatsapp_url = WhatsAppUrlGenerator::generateFromProduct($p);
        });

        return view('livewire.show-product', [
            'whatsappUrl' => WhatsAppUrlGenerator::generateFromProduct($this->product),
            'relatedProducts' => $relatedProducts,
            'installmentPrice' => number_format($this->product->price / 3, 2, ',', '.'),
            'pixPrice' => number_format($this->product->price * 0.95, 2, ',', '.'),
        ]);
    }
}
