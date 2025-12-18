<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return back()->withErrors(['review' => 'لقد قمت بتقييم هذا المنتج من قبل']);
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false // Requires admin approval
        ]);

        return back()->with('success', 'شكراً! سيتم نشر تقييمك بعد المراجعة');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Only owner or admin can delete
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'تم حذف التقييم');
    }
}
