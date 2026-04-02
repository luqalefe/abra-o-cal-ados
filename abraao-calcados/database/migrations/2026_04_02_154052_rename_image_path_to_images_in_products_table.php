<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'images')) {
            Schema::table('products', function (Blueprint $schema) {
                $schema->json('images')->nullable()->after('price');
            });
        }

        // Migrate existing image_path to images (as single array element)
        if (Schema::hasColumn('products', 'image_path')) {
            DB::table('products')->whereNotNull('image_path')->orderBy('id')->each(function ($product) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['images' => json_encode([$product->image_path])]);
            });

            Schema::table('products', function (Blueprint $schema) {
                $schema->dropColumn('image_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $schema) {
            $schema->string('image_path')->nullable()->after('price');
        });

        DB::table('products')->whereNotNull('images')->each(function ($product) {
            $images = json_decode($product->images, true);
            if (!empty($images)) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['image_path' => $images[0]]);
            }
        });

        Schema::table('products', function (Blueprint $schema) {
            $schema->dropColumn('images');
        });
    }
};
