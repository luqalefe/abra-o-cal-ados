<?php

namespace App\Services;

use App\Models\Product;

class WhatsAppUrlGenerator
{
    public static function generate(string $productName, string $formattedPrice): string
    {
        $number = config('store.whatsapp_number');
        $message = "Olá! Gostaria de saber mais sobre o produto {$productName} no valor de {$formattedPrice}.";

        return "https://wa.me/{$number}?text=" . urlencode($message);
    }

    public static function generateFromProduct(Product $product): string
    {
        return static::generate($product->name, $product->formatted_price);
    }
}
