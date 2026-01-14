<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml
     */
    public function index(): Response
    {
        $products = Product::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();

        $categories = Category::whereNull('deleted_at')
            ->select('slug', 'updated_at')
            ->get();

        $content = view('sitemap', [
            'products' => $products,
            'categories' => $categories,
        ]);

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
