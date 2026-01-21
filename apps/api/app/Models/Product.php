<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'base_price',
        'discount_price',
        'category_id',
        'brand_id',
        'sku',
        'rating',
        'is_active',
        'is_featured',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get product's category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get product's brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get product variants
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get product images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get product reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate average rating
     */
    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->save();
    }

    /**
     * Get product images (legacy support)
     */
    public function image()
    {
        return $this->hasOne(ProductImage::class);
    }

    /**
     * Scope: Filter by price range
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    /**
     * Scope: Filter in stock products
     */
    public function scopeInStock($query)
    {
        return $query->whereHas('variants', function ($q) {
            $q->where('stock', '>', 0);
        });
    }
}
