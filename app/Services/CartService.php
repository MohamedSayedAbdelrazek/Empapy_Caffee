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

        // Single query to get all products
        $products = Product::whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            if ($products->has($id)) {
                $product = $products->get($id);
                $subtotal = $product->current_price * $item['quantity'];

                $items[] = [
                    'id' => $product->id,
                    'product' => $product,
                    'name' => $product->name,
                    'name_ar' => $product->name_ar,
                    'image' => $product->image,
                    'price' => $product->current_price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal
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
    public function addToCart(Product $product, int $quantity = 1): array
    {
        // Check if product is active
        if (!$product->is_active) {
            return [
                'success' => false,
                'message' => 'هذا المنتج غير متوفر حالياً'
            ];
        }

        $cart = $this->getCart();
        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $totalRequested = $currentQty + $quantity;

        // Check stock availability
        if ($product->stock < $totalRequested) {
            return [
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة في المخزون. المتوفر: ' . $product->stock
            ];
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = ['quantity' => $quantity];
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
    public function updateQuantity(int $productId, int $quantity): array
    {
        $cart = $this->getCart();

        if ($quantity === 0) {
            unset($cart[$productId]);
        } else {
            // Check stock before updating
            $product = Product::find($productId);
            if ($product && $product->stock < $quantity) {
                return [
                    'success' => false,
                    'message' => 'الكمية المطلوبة غير متوفرة. المتوفر: ' . $product->stock
                ];
            }

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
            }
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
    public function removeFromCart(int $productId): array
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
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
        $cart = $this->getCart();

        if (empty($cart)) {
            return 0.0;
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $total = 0.0;

        foreach ($cart as $id => $item) {
            if ($products->has($id)) {
                $total += $products->get($id)->current_price * $item['quantity'];
            }
        }

        return $total;
    }

    /**
     * Validate cart items (check stock and availability)
     */
    public function validateCart(): array
    {
        $cart = $this->getCart();
        $errors = [];
        $validItems = [];

        if (empty($cart)) {
            return ['valid' => true, 'errors' => [], 'items' => []];
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        foreach ($cart as $id => $item) {
            if (!$products->has($id)) {
                $errors[] = "منتج غير موجود في السلة";
                continue;
            }

            $product = $products->get($id);

            if (!$product->is_active) {
                $errors[] = "المنتج '{$product->name_ar}' غير متوفر حالياً";
                continue;
            }

            if ($product->stock < $item['quantity']) {
                $errors[] = "الكمية المطلوبة من '{$product->name_ar}' غير متوفرة. المتوفر: {$product->stock}";
                continue;
            }

            $validItems[$id] = $item;
        }

        // Update cart to only include valid items
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
