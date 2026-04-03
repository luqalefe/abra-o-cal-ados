<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_promoted');
            $table->index(['is_promoted', 'category_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_promoted']);
            $table->dropIndex(['is_promoted', 'category_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
