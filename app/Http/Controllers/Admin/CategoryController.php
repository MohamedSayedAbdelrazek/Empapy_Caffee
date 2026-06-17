<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Handle image upload with automatic WebP conversion
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $customName = time() . '_' . Str::slug($validated['name']);
            $result = ImageService::uploadAndConvert($image, 'uploads/categories', $customName);
            $validated['image'] = $result['path'];
        }

        Category::create($validated);

        // Clear cache automatically so changes appear immediately
        Artisan::call('optimize:clear');

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح');
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load('products');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle image upload with automatic WebP conversion
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is a local file
            if ($category->image && str_starts_with($category->image, '/uploads/')) {
                ImageService::delete($category->image);
            }

            $image = $request->file('image');
            $customName = time() . '_' . Str::slug($validated['name']);
            $result = ImageService::uploadAndConvert($image, 'uploads/categories', $customName);
            $validated['image'] = $result['path'];
        }

        $category->update($validated);

        // Clear cache automatically so changes appear immediately
        Artisan::call('optimize:clear');

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Count trashed products too: the DB enforces ON DELETE RESTRICT
        // (DATA-01), and a soft-deleted product still holds the foreign key.
        if ($category->products()->withTrashed()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف فئة تحتوي على منتجات. يرجى نقل المنتجات إلى فئة أخرى أو حذفها نهائياً أولاً.');
        }

        $category->delete();

        // Clear cache automatically so changes appear immediately
        Artisan::call('optimize:clear');

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}
