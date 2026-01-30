<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display cart page
     */
    public function index()
    {
        $cartData = $this->cartService->getCartWithProducts();

        $freeShippingThreshold = \App\Models\Setting::get('shipping_free_threshold', 500);
        $shippingFee = \App\Models\Setting::get('shipping_fee', 50);

        return view('cart.index', [
            'cartItems' => $cartData['items'],
            'total' => $cartData['total'],
            'freeShippingThreshold' => $freeShippingThreshold,
            'shippingFee' => $shippingFee,
        ]);
    }

    /**
     * Add product to cart (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10',
            'options' => 'nullable|array'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->get('quantity', 1);
        $options = $request->get('options', []);

        $result = $this->cartService->addToCart($product, $quantity, $options);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    /**
     * Update cart item quantity (AJAX)
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        $result = $this->cartService->updateQuantity(
            $request->key,
            $request->quantity
        );

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        // Get updated cart data
        $cartData = $this->cartService->getCartWithProducts();

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'تم تحديث السلة بنجاح',
            'cart' => [
                'items' => $cartData['items'],
                'total' => $cartData['total'],
                'count' => $cartData['count']
            ]
        ]);
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string'
        ]);

        $result = $this->cartService->removeFromCart($request->key);

        return response()->json($result);
    }

    /**
     * Clear entire cart (AJAX)
     */
    public function clear()
    {
        $result = $this->cartService->clearCart();

        return response()->json($result);
    }

    /**
     * Get cart data (AJAX)
     */
    public function getCart()
    {
        $cartData = $this->cartService->getCartWithProducts();

        return response()->json([
            'cartCount' => $cartData['count'],
            'cartTotal' => $cartData['total'],
            'items' => $cartData['items']
        ]);
    }
}
