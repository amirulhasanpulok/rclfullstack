<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $category;
    protected $brand;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::factory()->create();
        $this->brand = Brand::factory()->create();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_list_products()
    {
        Product::factory(15)->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'base_price',
                        'category_id',
                        'brand_id',
                        'is_active',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ]
            ])
            ->assertJson(['success' => true])
            ->assertJsonCount(15, 'data');
    }

    /** @test */
    public function can_get_single_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
        ]);

        $response = $this->getJson("/api/v1/products/{$product->slug}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'base_price' => $product->base_price,
                ]
            ]);
    }

    /** @test */
    public function can_create_product_when_authenticated()
    {
        $this->user->assignRole('admin');
        $this->user->givePermissionTo('create products');

        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'base_price' => 99.99,
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', $productData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Test Product',
                    'slug' => 'test-product',
                    'base_price' => 99.99,
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'slug' => 'test-product',
        ]);
    }

    /** @test */
    public function cannot_create_product_without_permission()
    {
        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'base_price' => 99.99,
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', $productData);

        $response->assertStatus(403);
    }

    /** @test */
    public function cannot_create_product_without_authentication()
    {
        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'base_price' => 99.99,
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(401);
    }

    /** @test */
    public function validates_required_fields()
    {
        $this->user->assignRole('admin');
        $this->user->givePermissionTo('create products');

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => '', // Required but empty
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'slug', 'base_price', 'category_id']);
    }

    /** @test */
    public function can_update_product()
    {
        $this->user->assignRole('admin');
        $this->user->givePermissionTo('edit products');

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'name' => 'Updated Name',
                'base_price' => 199.99,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Updated Name',
                    'base_price' => 199.99,
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function can_delete_product()
    {
        $this->user->assignRole('admin');
        $this->user->givePermissionTo('delete products');

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function can_get_featured_products()
    {
        Product::factory(5)->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Product::factory(10)->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_featured' => false,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/products/featured');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function paginated_results_work_correctly()
    {
        Product::factory(35)->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/products?page=2&per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.total', 35);
    }
}
