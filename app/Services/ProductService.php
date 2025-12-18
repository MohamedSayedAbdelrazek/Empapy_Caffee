<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product
     */
    public function createProduct(array $data): Product
    {
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_active'] = $data['is_active'] ?? true;

        $product = Product::create($data);

        return $product;
    }

    /**
     * Update an existing product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_active'] = $data['is_active'] ?? false;

        $product->update($data);

        return $product->fresh();
    }

    /**
     * Delete a product (soft delete)
     */
    public function deleteProduct(Product $product): bool
    {
        // Delete associated images
        $this->deleteProductImages($product);

        return $product->delete();
    }

    /**
     * Restore a soft-deleted product
     */
    public function restoreProduct(Product $product): bool
    {
        return $product->restore();
    }

    /**
     * Force delete a product permanently
     */
    public function forceDeleteProduct(Product $product): bool
    {
        $this->deleteProductImages($product);
        return $product->forceDelete();
    }

    /**
     * Handle main image upload
     */
    public function handleImageUpload(?UploadedFile $image, ?string $oldImagePath = null): ?string
    {
        if (!$image) {
            return null;
        }

        // Delete old image if exists
        if ($oldImagePath) {
            $this->deleteImage($oldImagePath);
        }

        $path = $image->store('products', 'public');
        return '/storage/' . $path;
    }

    /**
     * Handle gallery images upload
     */
    public function handleGalleryUpload(?array $galleryImages, ?array $oldGallery = null): ?array
    {
        if (empty($galleryImages)) {
            return null;
        }

        // Delete old gallery images
        if ($oldGallery) {
            foreach ($oldGallery as $oldImage) {
                $this->deleteImage($oldImage);
            }
        }

        $galleryPaths = [];
        foreach ($galleryImages as $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store('products/gallery', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
        }

        return $galleryPaths;
    }

    /**
     * Delete a single image from storage
     */
    public function deleteImage(string $imagePath): bool
    {
        // Convert public path to storage path
        $storagePath = str_replace('/storage/', '', $imagePath);

        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->delete($storagePath);
        }

        return false;
    }

    /**
     * Delete all product images (main + gallery)
     */
    public function deleteProductImages(Product $product): void
    {
        // Delete main image
        if ($product->image) {
            $this->deleteImage($product->image);
        }

        // Delete gallery images
        if ($product->gallery) {
            $gallery = is_array($product->gallery) ? $product->gallery : json_decode($product->gallery, true);
            if ($gallery) {
                foreach ($gallery as $image) {
                    $this->deleteImage($image);
                }
            }
        }
    }

    /**
     * Get products with filters
     */
    public function getFilteredProducts(array $filters, int $perPage = 15)
    {
        $query = Product::with('category');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get trashed products
     */
    public function getTrashedProducts(int $perPage = 15)
    {
        return Product::onlyTrashed()->with('category')->latest()->paginate($perPage);
    }
}
