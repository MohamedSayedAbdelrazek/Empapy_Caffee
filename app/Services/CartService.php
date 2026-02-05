<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Get the current cart from session
     */
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Get cart with product details (optimized query)
     */
    public function getCartWithProducts(): array
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return [
                'items' => [],
                'total' => 0,
                'count' => 0
            ];
        }

        // Collect all product IDs from cart items
        $productIds = array_column($cart, 'product_id');

        // Single query to get all products
        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            $productId = $item['product_id'];

            if ($products->has($productId)) {
                $product = $products->get($productId);

                // Calculate unit price based on options
                $options = $item['options'] ?? [];
                // Map option types to their value IDs handling the structure {type: value_id}
                $optionValueIds = array_values($options);

                // Calculate dynamic price
                $unitPrice = $product->calculatePriceWithOptions($optionValueIds);
                $subtotal = $unitPrice * $item['quantity'];

                // Load option details for display - optimized to avoid N+1
                $optionDetails = [];
                if (!empty($optionValueIds)) {
                    $optionLabels = [
                        'weight' => 'الوزن',
                        'roast' => 'التحميص',
                        'additive' => 'الإضافات',
                        'flavor' => 'النكهة',
                        'size' => 'الحجم',
                    ];

                    // Load all option values in a single query
                    $optionValuesMap = \App\Models\ProductOptionValue::with('option')
                        ->whereIn('id', $optionValueIds)
                        ->get()
                        ->keyBy('id');

                    foreach ($options as $type => $valueId) {
                        if ($optionValuesMap->has($valueId)) {
                            $val = $optionValuesMap->get($valueId);
                            $optionDetails[] = [
                                'type' => $type,
                                'label' => $optionLabels[$type] ?? $val->option->name,
                                'value' => $val->value,
                                'price' => $val->price_modifier
                            ];
                        }
                    }
                }

                $items[] = [
                    'key' => $key, // Unique cart item key
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'product' => $product,
                    'name' => $product->name,
                    'name_ar' => $product->name,
                    'image' => $product->image,
                    'price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'options' => $optionDetails,
                    'raw_options' => $options
                ];

                $total += $subtotal;
            }
        }

        return [
            'items' => $items,
            'total' => $total,
            'count' => $this->getCartCount()
        ];
    }

    /**
     * Add product to cart
     */
    public function addToCart(Product $product, int $quantity = 1, array $options = []): array
    {
        // Check if product is active
        if (!$product->is_active) {
            return [
                'success' => false,
                'message' => 'هذا المنتج غير متوفر حالياً'
            ];
        }

        $cart = $this->getCart();

        // Generate unique key based on product ID and sorted options to distinguish variants
        // Sort options by key to ensure consistency
        ksort($options);
        $key = md5($product->id . serialize($options));

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'options' => $options
            ];
        }

        session()->put('cart', $cart);

        return [
            'success' => true,
            'message' => 'تمت الإضافة إلى السلة',
            'cartCount' => $this->getCartCount(),
            'cartTotal' => $this->getCartTotal()
        ];
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(string $key, int $quantity): array
    {
        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return ['success' => false, 'message' => 'المنتج غير موجود في السلة'];
        }

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        return [
            'success' => true,
            'cartCount' => $this->getCartCount(),
            'cartTotal' => $this->getCartTotal()
        ];
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(string $key): array
    {
        $cart = $this->getCart();
        unset($cart[$key]);
        session()->put('cart', $cart);

        return [
            'success' => true,
            'message' => 'تم الحذف من السلة',
            'cartCount' => $this->getCartCount(),
            'cartTotal' => $this->getCartTotal()
        ];
    }

    /**
     * Clear the entire cart
     */
    public function clearCart(): array
    {
        session()->forget('cart');

        return [
            'success' => true,
            'message' => 'تم تفريغ السلة',
            'cartCount' => 0,
            'cartTotal' => 0
        ];
    }

    /**
     * Get total number of items in cart
     */
    public function getCartCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart total price (optimized)
     */
    public function getCartTotal(): float
    {
        $data = $this->getCartWithProducts();
        return $data['total'];
    }

    /**
     * Validate cart items (check availability)
     */
    public function validateCart(): array
    {
        $cart = $this->getCart();
        $validItems = [];
        $errors = [];

        if (empty($cart)) {
            return ['valid' => true, 'errors' => [], 'items' => []];
        }

        $productIds = array_column($cart, 'product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cart as $key => $item) {
            $productId = $item['product_id'];

            if (!$products->has($productId)) {
                $errors[] = "منتج غير موجود";
                continue;
            }

            $product = $products->get($productId);
            if (!$product->is_active) {
                $errors[] = "المنتج '{$product->name}' غير متوفر حالياً";
                continue;
            }

            $validItems[$key] = $item;
        }

        if (count($validItems) !== count($cart)) {
            session()->put('cart', $validItems);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'items' => $validItems
        ];
    }
}
