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

                // Load option details for display
                // We need to fetch the value names, effectively
                // But for now, let's just pass the option IDs and let the view handle/or fetch them here
                // A better approach is to fetch OptionValues here
                // For simplicity in this step, we will rely on loading them if needed, or better:
                // Let's load the option values to pass to the view
                $optionDetails = [];
                if (!empty($optionValueIds)) {
                    // This N+1 is small (cart usually has few items), but we could optimize.
                    // For now, let's use the helper relation on product or model
                    foreach ($options as $type => $valueId) {
                        $val = \App\Models\ProductOptionValue::find($valueId);
                        if ($val) {
                            $optionDetails[] = [
                                'type' => $type,
                                'label' => $val->option->name_ar,
                                'value' => $val->value_ar,
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
                    'name_ar' => $product->name_ar,
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

        // Check stock availability (Global check for product, regardless of variant for now)
        // Aggregating quantity of this product in cart
        // Since stock is infinite (9999), this is less critical but good specific logic
        /*
        $currentQtyInCart = 0;
        foreach ($cart as $cartItem) {
            if ($cartItem['product_id'] == $product->id) {
                $currentQtyInCart += $cartItem['quantity'];
            }
        }
        $totalRequested = $currentQtyInCart + $quantity;
        
        if ($product->stock < $totalRequested) {
             return [
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة في المخزون. المتوفر: ' . $product->stock
            ];
        }
        */

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
            // Stock check skipped as per "Always Available" requirement
            // But if we needed it, we would check Product::find($cart[$key]['product_id'])->stock

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
     * Validate cart items (check stock and availability)
     */
    public function validateCart(): array
    {
        // Simple validation since stock is removed
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
                $errors[] = "المنتج '{$product->name_ar}' غير متوفر حالياً";
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
