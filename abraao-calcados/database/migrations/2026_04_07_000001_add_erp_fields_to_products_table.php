<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('erp_code', 20)->unique()->nullable()->after('id');
            $table->decimal('price_wholesale', 10, 2)->nullable()->after('price');
            $table->integer('stock')->default(0)->after('price_wholesale');
            $table->boolean('is_available')->default(true)->after('stock');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_available']);
            $table->dropUnique(['erp_code']);
            $table->dropColumn(['erp_code', 'price_wholesale', 'stock', 'is_available']);
        });
    }
};
