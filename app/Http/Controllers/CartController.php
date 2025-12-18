<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
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

        return view('cart.index', [
            'cartItems' => $cartData['items'],
            'total' => $cartData['total']
        ]);
    }

    /**
     * Add product to cart (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->get('quantity', 1);

        $result = $this->cartService->addToCart($product, $quantity);

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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        $result = $this->cartService->updateQuantity(
            $request->product_id,
            $request->quantity
        );

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $result = $this->cartService->removeFromCart($request->product_id);

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
