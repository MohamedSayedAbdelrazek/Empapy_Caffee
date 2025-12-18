<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display wishlist page
     */
    public function index()
    {
        $wishlistItems = Wishlist::getForUser()
            ->with('product.category')
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Toggle product in wishlist (AJAX)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = auth()->id();
        $sessionId = session()->getId();

        // Find existing wishlist item
        $wishlistItem = auth()->check()
            ? Wishlist::where('user_id', $userId)->where('product_id', $productId)->first()
            : Wishlist::where('session_id', $sessionId)->where('product_id', $productId)->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            return response()->json([
                'success' => true,
                'added' => false,
                'message' => 'تم الحذف من المفضلة'
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'session_id' => auth()->check() ? null : $sessionId,
                'product_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'added' => true,
                'message' => 'تمت الإضافة للمفضلة'
            ]);
        }
    }

    /**
     * Get wishlist count (AJAX)
     */
    public function count()
    {
        $count = Wishlist::getForUser()->count();
        return response()->json(['count' => $count]);
    }
}
