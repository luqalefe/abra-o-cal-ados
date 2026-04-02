<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Seed products with real images.
     * Images should be at: database/seeders/images/
     */
    public function run(): void
    {
        $products = [
            // === TÊNIS ===
            'Tênis' => [
                [
                    'name' => 'Tênis Esportivo Runner Pro',
                    'description' => 'Tênis de corrida leve e confortável, ideal para treinos diários. Solado em EVA com amortecimento superior.',
                    'price' => 189.90,
                    'image' => 'tenis_01.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Tênis Casual Urban Style',
                    'description' => 'Tênis casual moderno perfeito para o dia a dia. Design clean e versátil.',
                    'price' => 159.90,
                    'image' => 'tenis_02.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Tênis Feminino Fit Active',
                    'description' => 'Tênis feminino leve para academia e caminhadas. Cabedal respirável e sola flexível.',
                    'price' => 149.90,
                    'image' => 'tenis_03.png',
                    'is_promoted' => true,
                ],
            ],

            // === SANDÁLIAS ===
            'Sandálias' => [
                [
                    'name' => 'Sandália Rasteira Couro Natural',
                    'description' => 'Sandália rasteira em couro legítimo. Elegância e conforto para o verão.',
                    'price' => 99.90,
                    'image' => 'sandalia_01.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Sandália Salto Festa Noite',
                    'description' => 'Sandália de salto alto com tiras elegantes. Perfeita para festas e eventos.',
                    'price' => 129.90,
                    'image' => 'sandalia_02.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Sandália Masculina Slide Couro',
                    'description' => 'Slide masculino em couro marrom. Casual e confortável para o dia a dia.',
                    'price' => 79.90,
                    'image' => 'sandalia_03.png',
                    'is_promoted' => true,
                ],
            ],

            // === SAPATOS SOCIAIS ===
            'Sapatos Sociais' => [
                [
                    'name' => 'Oxford Clássico Preto',
                    'description' => 'Sapato social oxford em couro polido. O clássico que nunca sai de moda.',
                    'price' => 219.90,
                    'image' => 'sapato_social_01.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Derby Couro Cognac',
                    'description' => 'Sapato social derby em couro marrom cognac. Perfeito para o ambiente corporativo.',
                    'price' => 199.90,
                    'image' => 'sapato_social_02.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Scarpin Feminino Clássico',
                    'description' => 'Scarpin preto em couro legítimo. Elegância atemporal para todas as ocasiões.',
                    'price' => 179.90,
                    'image' => 'sapato_social_03.png',
                    'is_promoted' => true,
                ],
            ],

            // === CHINELOS ===
            'Chinelos' => [
                [
                    'name' => 'Chinelo Confort Verão',
                    'description' => 'Chinelo de dedo estilo clássico. Leve, macio e ideal para o calor.',
                    'price' => 29.90,
                    'image' => 'chinelo_01.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Slide Confort Home',
                    'description' => 'Slide para casa com solado acolchoado. Máximo conforto para os pés.',
                    'price' => 39.90,
                    'image' => 'chinelo_02.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Slide Esportivo Athletic',
                    'description' => 'Slide esportivo com design moderno. Pós-treino com estilo.',
                    'price' => 49.90,
                    'image' => 'chinelo_03.png',
                    'is_promoted' => true,
                ],
            ],

            // === BOTAS ===
            'Botas' => [
                [
                    'name' => 'Bota Coturno Adventure',
                    'description' => 'Bota coturno em couro marrom. Resistente e estilosa para aventuras.',
                    'price' => 249.90,
                    'image' => 'bota_01.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Bota Chelsea Feminina',
                    'description' => 'Bota Chelsea preta com elástico lateral. Moderna, prática e elegante.',
                    'price' => 199.90,
                    'image' => 'bota_02.png',
                    'is_promoted' => true,
                ],
                [
                    'name' => 'Coturno Militar Black Ops',
                    'description' => 'Coturno militar preto com solado tratorado. Estilo e durabilidade.',
                    'price' => 279.90,
                    'image' => 'bota_03.png',
                    'is_promoted' => true,
                ],
            ],
        ];

        $imagesPath = database_path('seeders/images');

        foreach ($products as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                continue;
            }

            foreach ($items as $item) {
                $imagePath = null;

                // Copy image to storage/app/public/products/ if it exists
                $sourceImage = $imagesPath . '/' . $item['image'];
                if (File::exists($sourceImage)) {
                    $storagePath = 'products/' . $item['image'];
                    Storage::disk('public')->put($storagePath, File::get($sourceImage));
                    $imagePath = $storagePath;
                }

                Product::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'images' => $imagePath ? [$imagePath] : null,
                    'is_promoted' => $item['is_promoted'],
                ]);
            }
        }
    }
}
