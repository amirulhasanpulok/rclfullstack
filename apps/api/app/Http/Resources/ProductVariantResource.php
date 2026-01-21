<?php

namespace App\Http\Resources;

class ProductVariantResource extends ApiResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'color' => $this->color,
            'size' => $this->size,
            'weight' => $this->weight,
            'stock' => $this->stock,
            'price_adjustment' => $this->price_adjustment,
            'is_active' => $this->is_active,
        ];
    }
}
