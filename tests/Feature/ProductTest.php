<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create category
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function admin_can_view_products_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    /** @test */
    public function admin_can_view_create_product_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
    }

    /** @test */
    public function admin_can_create_product()
    {
        Storage::fake('public');

        $productData = [
            'name' => 'Test Coffee',
            'name_ar' => 'قهوة اختبار',
            'category_id' => $this->category->id,
            'description' => 'Test description',
            'description_ar' => 'وصف اختباري',
            'price' => 99.99,
            'stock' => 50,
            'weight' => '250g',
            'roast_level' => 'medium',
            'origin' => 'Ethiopia',
            'origin_ar' => 'إثيوبيا',
            'is_featured' => true,
            'is_active' => true,
            'image' => UploadedFile::fake()->image('product.jpg'),
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Coffee',
            'name_ar' => 'قهوة اختبار',
            'category_id' => $this->category->id,
        ]);
    }

    /** @test */
    public function admin_can_update_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $updateData = [
            'name' => 'Updated Coffee',
            'name_ar' => 'قهوة محدثة',
            'category_id' => $this->category->id,
            'price' => 149.99,
            'stock' => 100,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), $updateData);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Coffee',
            'name_ar' => 'قهوة محدثة',
        ]);
    }

    /** @test */
    public function admin_can_delete_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));

        // Soft delete check
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function product_requires_name()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name_ar' => 'قهوة',
                'category_id' => $this->category->id,
                'price' => 99.99,
                'stock' => 50,
            ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function product_requires_valid_category()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Coffee',
                'name_ar' => 'قهوة اختبار',
                'category_id' => 99999,
                'price' => 99.99,
                'stock' => 50,
            ]);

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function sale_price_must_be_less_than_price()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Coffee',
                'name_ar' => 'قهوة اختبار',
                'category_id' => $this->category->id,
                'price' => 50.00,
                'sale_price' => 100.00,
                'stock' => 50,
            ]);

        $response->assertSessionHasErrors('sale_price');
    }

    /** @test */
    public function guest_cannot_access_admin_products()
    {
        $response = $this->get(route('admin.products.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function regular_user_cannot_access_admin_products()
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.products.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function product_can_be_filtered_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['category' => $category1->id]));

        $response->assertStatus(200);
    }

    /** @test */
    public function product_can_be_searched_by_name()
    {
        Product::factory()->create([
            'name' => 'Ethiopian Blend',
            'name_ar' => 'خلطة إثيوبية',
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['search' => 'Ethiopian']));

        $response->assertStatus(200);
    }
}
