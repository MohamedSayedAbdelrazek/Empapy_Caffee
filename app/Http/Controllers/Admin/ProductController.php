<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $products = $this->productService->getFilteredProducts([
            'search' => $request->search,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Handle option flags
        $validated['has_weight_options'] = $request->has('has_weight_options');
        $validated['has_roast_options'] = $request->has('has_roast_options');
        $validated['has_additive_options'] = $request->has('has_additive_options');
        $validated['has_flavor_options'] = $request->has('has_flavor_options');

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $this->productService->handleImageUpload($request->file('image'));
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $galleryPaths = $this->productService->handleGalleryUpload($request->file('gallery'));
            $validated['gallery'] = $galleryPaths;
        }

        $product = $this->productService->createProduct($validated);

        // Sync product options
        $this->productService->syncProductOptions($product, [
            'weight_values' => $request->input('weight_values', []),
            'roast_values' => $request->input('roast_values', []),
            'additive_values' => $request->input('additive_values', []),
            'flavor_values' => $request->input('flavor_values', []),
            'additive_weight_prices' => $request->input('additive_weight_prices', []),
        ]);

        // Clear product caches so the new product appears immediately
        $this->clearProductCaches();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the product
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get();

        // Load options with values
        $product->load(['options.values']);

        // Prepare options data for the form
        $weightValues = $product->weight_values;
        $roastValues = $product->roast_values;
        $additiveValues = $product->additive_values;
        $flavorValues = $product->flavor_values;

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'weightValues',
            'roastValues',
            'additiveValues',
            'flavorValues'
        ));
    }

    /**
     * Update the specified product
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validated = $request->validated();

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Handle option flags
        $validated['has_weight_options'] = $request->has('has_weight_options');
        $validated['has_roast_options'] = $request->has('has_roast_options');
        $validated['has_additive_options'] = $request->has('has_additive_options');
        $validated['has_flavor_options'] = $request->has('has_flavor_options');

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $this->productService->handleImageUpload(
                $request->file('image'),
                $product->image
            );
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $validated['gallery'] = $this->productService->handleGalleryUpload(
                $request->file('gallery'),
                $product->gallery
            );
        }

        $this->productService->updateProduct($product, $validated);

        // Sync product options
        $product->refresh(); // Refresh to get updated option flags
        $this->productService->syncProductOptions($product, [
            'weight_values' => $request->input('weight_values', []),
            'roast_values' => $request->input('roast_values', []),
            'additive_values' => $request->input('additive_values', []),
            'flavor_values' => $request->input('flavor_values', []),
            'additive_weight_prices' => $request->input('additive_weight_prices', []),
        ]);

        // Clear product caches
        $this->clearProductCaches();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified product (soft delete)
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        // Clear product caches
        $this->clearProductCaches();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * Display trashed products
     */
    public function trashed()
    {
        $products = $this->productService->getTrashedProducts();
        return view('admin.products.trashed', compact('products'));
    }

    /**
     * Restore a soft-deleted product
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->productService->restoreProduct($product);

        // Clear product caches
        $this->clearProductCaches();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم استعادة المنتج بنجاح');
    }

    /**
     * Force delete a product permanently
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->productService->forceDeleteProduct($product);

        // Clear product caches
        $this->clearProductCaches();

        return redirect()->route('admin.products.trashed')
            ->with('success', 'تم حذف المنتج نهائياً');
    }

    /**
     * Clear all product-related caches
     */
    protected function clearProductCaches(): void
    {
        Cache::forget('home_featured_products');
        Cache::forget('home_latest_products');
        Cache::forget('home_categories');
    }
}
