<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

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

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $this->productService->handleImageUpload($request->file('image'));
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $galleryPaths = $this->productService->handleGalleryUpload($request->file('gallery'));
            $validated['gallery'] = $galleryPaths;
        }

        $this->productService->createProduct($validated);

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
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validated = $request->validated();

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

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

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified product (soft delete)
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

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

        return redirect()->route('admin.products.trashed')
            ->with('success', 'تم حذف المنتج نهائياً');
    }
}
