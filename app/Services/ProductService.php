<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
                    ->orWhere('description', 'like', "%{$search}%");
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

    /**
     * Sync product options (weight, roast, additive)
     */
    public function syncProductOptions(Product $product, array $optionsData): void
    {
        DB::transaction(function () use ($product, $optionsData) {
            // Sync weight options
            if (isset($optionsData['weight_values']) && $product->has_weight_options) {
                $this->syncOptionType($product, ProductOption::TYPE_WEIGHT, $optionsData['weight_values']);
            } else {
                // Delete existing weight options if disabled
                $this->deleteOptionType($product, ProductOption::TYPE_WEIGHT);
            }

            // Sync roast options
            if (isset($optionsData['roast_values']) && $product->has_roast_options) {
                $this->syncOptionType($product, ProductOption::TYPE_ROAST, $optionsData['roast_values']);
            } else {
                $this->deleteOptionType($product, ProductOption::TYPE_ROAST);
            }

            // Sync additive options
            if (isset($optionsData['additive_values']) && $product->has_additive_options) {
                $this->syncOptionType($product, ProductOption::TYPE_ADDITIVE, $optionsData['additive_values']);
            } else {
                $this->deleteOptionType($product, ProductOption::TYPE_ADDITIVE);
            }
        });
    }

    /**
     * Sync a specific option type (weight/roast/additive)
     */
    protected function syncOptionType(Product $product, string $type, array $values): void
    {
        if (empty($values)) {
            $this->deleteOptionType($product, $type);
            return;
        }

        // Get or create the option group
        $option = ProductOption::firstOrCreate(
            ['product_id' => $product->id, 'type' => $type],
            [
                'name' => $this->getOptionTypeName($type),
                'name_ar' => $this->getOptionTypeNameAr($type),
                'sort_order' => $this->getOptionTypeSortOrder($type),
            ]
        );

        // Get existing value IDs
        $existingValueIds = $option->values()->pluck('id')->toArray();
        $updatedValueIds = [];

        // Process each value
        foreach ($values as $index => $valueData) {
            // Accept both 'value' (from form) and 'value_ar' (for compatibility)
            $valueText = $valueData['value'] ?? $valueData['value_ar'] ?? null;

            if (empty($valueText)) {
                continue;
            }

            $valueId = $valueData['id'] ?? null;

            if ($valueId && in_array($valueId, $existingValueIds)) {
                // Update existing value
                ProductOptionValue::where('id', $valueId)->update([
                    'value' => $valueText,
                    'price_modifier' => floatval($valueData['price_modifier'] ?? 0),
                    'is_default' => isset($valueData['is_default']) && $valueData['is_default'],
                    'sort_order' => $index,
                ]);
                $updatedValueIds[] = $valueId;
            } else {
                // Create new value
                $newValue = ProductOptionValue::create([
                    'product_option_id' => $option->id,
                    'value' => $valueText,
                    'price_modifier' => floatval($valueData['price_modifier'] ?? 0),
                    'is_default' => isset($valueData['is_default']) && $valueData['is_default'],
                    'sort_order' => $index,
                ]);
                $updatedValueIds[] = $newValue->id;
            }
        }

        // Delete values that were not updated (removed by user)
        $valuesToDelete = array_diff($existingValueIds, $updatedValueIds);
        if (!empty($valuesToDelete)) {
            ProductOptionValue::whereIn('id', $valuesToDelete)->delete();
        }

        // Ensure at least one default is set
        $this->ensureDefaultValue($option);
    }

    /**
     * Delete all options of a specific type for a product
     */
    protected function deleteOptionType(Product $product, string $type): void
    {
        // Options and their values will be deleted via cascade
        ProductOption::where('product_id', $product->id)
            ->where('type', $type)
            ->delete();
    }

    /**
     * Delete all product options
     */
    public function deleteProductOptions(Product $product): void
    {
        ProductOption::where('product_id', $product->id)->delete();
    }

    /**
     * Ensure at least one value is marked as default
     */
    protected function ensureDefaultValue(ProductOption $option): void
    {
        $hasDefault = $option->values()->where('is_default', true)->exists();

        if (!$hasDefault) {
            $firstValue = $option->values()->orderBy('sort_order')->first();
            if ($firstValue) {
                $firstValue->update(['is_default' => true]);
            }
        }
    }

    /**
     * Get English name for option type
     */
    protected function getOptionTypeName(string $type): string
    {
        return match ($type) {
            ProductOption::TYPE_WEIGHT => 'Weight',
            ProductOption::TYPE_ROAST => 'Roasting Level',
            ProductOption::TYPE_ADDITIVE => 'Additives',
            default => $type,
        };
    }

    /**
     * Get Arabic name for option type
     */
    protected function getOptionTypeNameAr(string $type): string
    {
        return match ($type) {
            ProductOption::TYPE_WEIGHT => 'الوزن',
            ProductOption::TYPE_ROAST => 'التحميص',
            ProductOption::TYPE_ADDITIVE => 'الإضافات',
            default => $type,
        };
    }

    /**
     * Get sort order for option type (weight first, then roast, then additives)
     */
    protected function getOptionTypeSortOrder(string $type): int
    {
        return match ($type) {
            ProductOption::TYPE_WEIGHT => 1,
            ProductOption::TYPE_ROAST => 2,
            ProductOption::TYPE_ADDITIVE => 3,
            default => 99,
        };
    }
}
