<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get single product with all options for modal
     */
    public function show($id)
    {
        $product = Product::with(['category', 'options.values'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Format options for frontend
        $formattedOptions = [];
        foreach ($product->options as $option) {
            foreach ($option->values as $value) {
                $formattedOptions[] = [
                    'id' => $value->id,
                    'type' => $option->type,
                    'option_name' => $option->name,
                    'value' => $value->value,
                    'price_modifier' => floatval($value->price_modifier),
                    'is_default' => $value->is_default,
                ];
            }
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => floatval($product->price),
            'sale_price' => $product->sale_price ? floatval($product->sale_price) : null,
            'current_price' => floatval($product->current_price),
            'image' => $product->image,
            'is_on_sale' => $product->is_on_sale,
            'has_options' => $product->has_options,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null,
            'options' => $formattedOptions,
        ]);
    }
}
