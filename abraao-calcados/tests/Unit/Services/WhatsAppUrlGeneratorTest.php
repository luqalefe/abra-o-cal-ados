<?php

use App\Services\WhatsAppUrlGenerator;
use App\Models\Product;

it('generates correct whatsapp url with product info', function () {
    config(['store.whatsapp_number' => '5511999999999']);
    
    $url = WhatsAppUrlGenerator::generate('Tênis Nike Air', 'R$ 299,90');
    $decodedUrl = urldecode($url);

    expect($url)->toContain('https://wa.me/5511999999999')
        ->and($decodedUrl)->toContain('Tênis Nike Air')
        ->and($decodedUrl)->toContain('R$ 299,90')
        ->and($decodedUrl)->toContain('Olá! Gostaria de saber mais sobre o produto');
});

it('generates url from product model', function () {
    config(['store.whatsapp_number' => '5511999999999']);
    
    $product = Product::factory()->make([
        'name' => 'Sandália Summer',
        'price' => 149.90,
    ]);

    $url = WhatsAppUrlGenerator::generateFromProduct($product);
    $decodedUrl = urldecode($url);

    expect($url)->toContain('https://wa.me/5511999999999')
        ->and($decodedUrl)->toContain('Sandália Summer');
});

it('encodes special characters in url', function () {
    config(['store.whatsapp_number' => '5511999999999']);
    
    $url = WhatsAppUrlGenerator::generate('Tênis & Sapatos', 'R$ 99,90');

    expect($url)->toContain('https://wa.me/')
        ->and($url)->not->toContain(' ');
});
