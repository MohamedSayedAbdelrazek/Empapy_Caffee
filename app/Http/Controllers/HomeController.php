<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        // Cache featured products for 30 minutes
        $featuredProducts = Cache::remember('home_featured_products', 1800, function () {
            return Product::with(['category', 'options.values'])
                ->active()
                ->featured()
                ->take(8)
                ->get();
        });

        // Cache categories for 1 hour
        $categories = Cache::remember('home_categories', 3600, function () {
            return Category::active()
                ->withCount('products')
                ->get();
        });

        // Cache latest products for 15 minutes
        $latestProducts = Cache::remember('home_latest_products', 900, function () {
            return Product::with(['category', 'options.values'])
                ->active()
                ->latest()
                ->take(4)
                ->get();
        });

        // Load wishlist IDs once to avoid N+1 in product cards
        $wishlistIds = auth()->check()
            ? Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray()
            : [];

        return view('home', compact(
            'featuredProducts',
            'categories',
            'latestProducts',
            'wishlistIds'
        ));
    }

    /**
     * Display about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'يرجى إدخال الاسم',
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'subject.required' => 'يرجى إدخال الموضوع',
            'message.required' => 'يرجى إدخال الرسالة',
        ]);

        $contact = ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Send push notification to admin
        try {
            $firebaseService = new \App\Services\FirebaseNotificationService();
            $firebaseService->notifyNewContactMessage($contact);
        } catch (\Exception $e) {
            \Log::error('[FCM] Failed to send contact notification: ' . $e->getMessage());
        }

        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
    }
}
