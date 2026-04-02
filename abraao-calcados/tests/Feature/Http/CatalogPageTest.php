<?php

use function Pest\Laravel\get;

it('returns 200 on homepage', function () {
    get('/')->assertStatus(200);
});

it('displays store name in page title', function () {
    config(['store.name' => 'Abraão Calçados Test']);
    
    get('/')
        ->assertSee('Abraão Calçados Test');
});
