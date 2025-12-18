<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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
            return Product::with('category')
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
            return Product::with('category')
                ->active()
                ->latest()
                ->take(4)
                ->get();
        });

        return view('home', compact(
            'featuredProducts',
            'categories',
            'latestProducts'
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
}
