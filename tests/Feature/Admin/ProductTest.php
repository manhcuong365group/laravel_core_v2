<?php

namespace Tests\Feature\Admin;

use App\Livewire\Backend\Products\CreatePage;
use App\Livewire\Backend\Products\EditPage;
use App\Livewire\Backend\Products\IndexPage;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\AdminPhaseOneMenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure the table exists before seeding
        if (!Schema::hasTable('admin_menus')) {
            $this->artisan('migrate');
        }

        $this->seed(RolePermissionSeeder::class);
        $this->seed(AdminPhaseOneMenuSeeder::class);
    }

    public function test_admin_can_view_product_index(): void
    {
        $admin = $this->createUserWithRole('admin');

        Livewire::actingAs($admin)
            ->test(IndexPage::class)
            ->assertStatus(200)
            ->assertSee('Quản lý sản phẩm');
    }

    public function test_editor_can_view_product_index(): void
    {
        $editor = $this->createUserWithRole('editor');

        Livewire::actingAs($editor)
            ->test(IndexPage::class)
            ->assertStatus(200);
    }

    public function test_admin_can_create_product(): void
    {
        $admin = $this->createUserWithRole('admin');
        $category = Category::factory()->create(['type' => 'product']);
        $brand = Brand::factory()->create();

        Livewire::actingAs($admin)
            ->test(CreatePage::class)
            ->set('name', 'Test Product')
            ->set('slug', 'test-product')
            ->set('price', '1.000.000')
            ->set('category_id', $category->id)
            ->set('brand_id', $brand->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('backend.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 1000000,
            'category_id' => $category->id,
        ]);
    }

    public function test_editor_cannot_create_product(): void
    {
        $editor = $this->createUserWithRole('editor');

        Livewire::actingAs($editor)
            ->test(CreatePage::class)
            ->assertForbidden();
    }

    public function test_admin_can_update_product(): void
    {
        $admin = $this->createUserWithRole('admin');
        $product = Product::factory()->create(['price' => 500000]);

        Livewire::actingAs($admin)
            ->test(EditPage::class, ['product' => $product])
            ->set('name', 'Updated Product Name')
            ->set('price', '750.000')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('backend.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'price' => 750000,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = $this->createUserWithRole('admin');
        $product = Product::factory()->create();

        Livewire::actingAs($admin)
            ->test(IndexPage::class)
            ->set('deleteTargetId', $product->id)
            ->call('deleteProduct')
            ->assertStatus(200);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_editor_cannot_delete_product(): void
    {
        $editor = $this->createUserWithRole('editor');
        $product = Product::factory()->create();

        Livewire::actingAs($editor)
            ->test(IndexPage::class)
            ->set('deleteTargetId', $product->id)
            ->call('deleteProduct')
            ->assertForbidden();
    }

    protected function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}

