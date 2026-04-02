<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Tênis',           'slug' => 'tenis'],
            ['name' => 'Sandálias',       'slug' => 'sandalias'],
            ['name' => 'Sapatos Sociais', 'slug' => 'sapatos-sociais'],
            ['name' => 'Chinelos',        'slug' => 'chinelos'],
            ['name' => 'Botas',           'slug' => 'botas'],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name'      => $data['name'],
                'slug'      => $data['slug'],
                'is_active' => true,
            ]);
        }
    }
}
