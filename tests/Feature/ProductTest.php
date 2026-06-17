<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Admin product CRUD against the current schema (no `name_ar` / `stock`
 * columns; slugs are generated server-side from the name).
 */
class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::factory()->create();
    }

    #[Test]
    public function an_admin_can_view_the_products_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertViewIs('admin.products.index');
    }

    #[Test]
    public function an_admin_can_create_a_product(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'قهوة اختبار',
            'category_id' => $this->category->id,
            'description' => 'وصف',
            'price' => 99.99,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'قهوة اختبار',
            'category_id' => $this->category->id,
            'price' => 99.99,
        ]);
    }

    #[Test]
    public function creating_a_product_requires_a_name(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'category_id' => $this->category->id,
                'price' => 50,
            ])
            ->assertSessionHasErrors('name');
    }

    #[Test]
    public function creating_a_product_requires_an_existing_category(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'قهوة',
                'category_id' => 999999,
                'price' => 50,
            ])
            ->assertSessionHasErrors('category_id');
    }

    #[Test]
    public function the_sale_price_must_be_lower_than_the_price(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'قهوة',
                'category_id' => $this->category->id,
                'price' => 50,
                'sale_price' => 100,
            ])
            ->assertSessionHasErrors('sale_price');
    }

    #[Test]
    public function an_admin_can_update_a_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'الاسم القديم',
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), [
                'name' => 'الاسم الجديد',
                'category_id' => $this->category->id,
                'price' => 149.99,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'الاسم الجديد',
            'price' => 149.99,
        ]);
    }

    #[Test]
    public function an_admin_can_soft_delete_a_product(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    // ---- Access control on the products routes ---------------------------

    #[Test]
    public function a_guest_is_redirected_to_login(): void
    {
        // StaffMiddleware sends non-staff to the login page.
        $this->get(route('admin.products.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function a_customer_is_not_treated_as_staff(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.products.index'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function a_cashier_without_the_permission_is_forbidden(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        $this->actingAs($cashier)
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    #[Test]
    public function a_cashier_with_the_permission_can_view_products(): void
    {
        $this->seed(PermissionSeeder::class);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('view-products');

        $this->actingAs($cashier)
            ->get(route('admin.products.index'))
            ->assertOk();
    }

    #[Test]
    public function a_cashier_with_create_permission_can_create_a_product(): void
    {
        // BUG-03 regression: the granted permission must actually work — the
        // form request authorisation must mirror the route's permission gate
        // rather than demanding the admin role.
        $this->seed(PermissionSeeder::class);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('create-products');

        $this->actingAs($cashier)->post(route('admin.products.store'), [
            'name' => 'قهوة الكاشير',
            'category_id' => $this->category->id,
            'price' => 75,
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['name' => 'قهوة الكاشير']);
    }

    #[Test]
    public function a_cashier_with_edit_permission_can_update_a_product(): void
    {
        // BUG-03 regression for the update path.
        $this->seed(PermissionSeeder::class);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('edit-products');
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $this->actingAs($cashier)->put(route('admin.products.update', $product), [
            'name' => 'اسم محدّث بواسطة الكاشير',
            'category_id' => $this->category->id,
            'price' => 88,
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'اسم محدّث بواسطة الكاشير',
        ]);
    }
}
