<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'current_price' => (float) $this->current_price,
            'discount_percentage' => $this->discount_percentage,
            'is_on_sale' => $this->is_on_sale,
            'stock' => (int) $this->stock,
            'in_stock' => $this->stock > 0,
            'image' => $this->image,
            'gallery' => $this->gallery,
            'weight' => $this->weight,
            'roast_level' => $this->roast_level,
            'roast_level_ar' => $this->getRoastLevelAr(),
            'origin' => $this->origin,
            'origin_ar' => $this->origin_ar,
            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'name_ar' => $this->category->name_ar,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get Arabic roast level label
     */
    private function getRoastLevelAr(): ?string
    {
        return match ($this->roast_level) {
            'light' => 'تحميص فاتح',
            'medium' => 'تحميص متوسط',
            'dark' => 'تحميص داكن',
            default => null,
        };
    }
}
