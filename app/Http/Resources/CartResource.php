<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => collect($this->resource['items'])->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'] ?? null,
                    'image' => $item['image'],
                    'price' => (float) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'subtotal' => (float) $item['subtotal'],
                ];
            }),
            'total' => (float) ($this->resource['total'] ?? 0),
            'count' => (int) ($this->resource['count'] ?? 0),
            'formatted_total' => number_format($this->resource['total'] ?? 0, 2) . ' ج.م',
        ];
    }
}
