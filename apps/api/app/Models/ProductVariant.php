<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'size',
        'weight',
        'stock',
        'price_adjustment',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'stock' => 'integer',
        'price_adjustment' => 'decimal:2',
    ];

    /**
     * Get the product this variant belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: Get in-stock variants
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Decrement stock
     */
    public function decrementStock($quantity)
    {
        $this->stock -= $quantity;
        $this->save();
    }

    /**
     * Increment stock
     */
    public function incrementStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
    }
}
