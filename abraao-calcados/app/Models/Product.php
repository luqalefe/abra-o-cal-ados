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
        'category_id',
        'name',
        'description',
        'price',
        'images',
        'is_promoted',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->price, in: 'BRL', locale: 'pt_BR'),
        );
    }
}
