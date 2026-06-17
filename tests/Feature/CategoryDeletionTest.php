<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * DATA-01: a category that still has products must not be deletable — guarded
 * both by the controller (friendly Arabic message, counts trashed products)
 * and, as the real backstop, by the database (ON DELETE RESTRICT).
 */
class CategoryDeletionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function an_empty_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[Test]
    public function a_category_with_an_active_product_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    #[Test]
    public function a_category_whose_only_product_is_soft_deleted_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product->delete(); // soft delete — still holds the foreign key

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    #[Test]
    public function the_database_refuses_to_delete_a_category_that_still_has_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        // Bypass the controller guard entirely — the ON DELETE RESTRICT foreign
        // key must still prevent the deletion (and the destructive cascade).
        $this->expectException(QueryException::class);

        $category->delete();
    }
}
