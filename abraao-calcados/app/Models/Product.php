<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id', // nullable — preenchido manualmente pelo admin após importação
        'erp_code',
        'name',
        'description',
        'price',
        'price_wholesale',
        'stock',
        'is_available',
        'images',
        'is_promoted',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_wholesale' => 'decimal:2',
        'stock' => 'integer',
        'is_available' => 'boolean',
        'is_promoted' => 'boolean',
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePromoted(Builder $query): void
    {
        $query->where('is_promoted', true);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_available', true);
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => preg_replace('/^\d+\s+/', '', $this->name),
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->price, in: 'BRL', locale: 'pt_BR'),
        );
    }
}
