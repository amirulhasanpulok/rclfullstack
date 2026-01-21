<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Create permissions
        Permission::firstOrCreate(['name' => 'create products']);
        Permission::firstOrCreate(['name' => 'edit products']);
        Permission::firstOrCreate(['name' => 'delete products']);
        Permission::firstOrCreate(['name' => 'view orders']);
        Permission::firstOrCreate(['name' => 'manage users']);

        $adminRole->syncPermissions([
            'create products',
            'edit products',
            'delete products',
            'view orders',
            'manage users',
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@nicolatetcholdiwsconsole.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Sports', 'slug' => 'sports'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[] = Category::firstOrCreate($cat)->id;
        }

        // Create brands
        $brands = [
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Sony', 'slug' => 'sony'],
        ];

        $brandIds = [];
        foreach ($brands as $brand) {
            $brandIds[] = Brand::firstOrCreate($brand)->id;
        }

        // Create sample products
        $productNames = [
            'Wireless Headphones',
            'USB-C Cable',
            'Screen Protector',
            'Phone Case',
            'Portable Charger',
            'Laptop Stand',
            'Keyboard',
            'Mouse Pad',
            'Monitor Arm',
            'Cable Organizer',
        ];

        foreach ($productNames as $index => $name) {
            $product = Product::firstOrCreate(
                ['slug' => str_slug($name)],
                [
                    'name' => $name,
                    'description' => "High-quality {$name} for your needs. Perfect for daily use.",
                    'base_price' => rand(19, 199) . '.99',
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'brand_id' => $brandIds[array_rand($brandIds)],
                    'is_active' => true,
                    'is_featured' => $index < 3,
                ]
            );

            // Create product images
            ProductImage::firstOrCreate([
                'product_id' => $product->id,
                'url' => "https://via.placeholder.com/500x500?text={$name}",
                'alt_text' => $name,
                'is_primary' => true,
            ]);

            // Create product variants
            $colors = ['Black', 'White', 'Blue', 'Red'];
            $sizes = ['S', 'M', 'L', 'XL'];

            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    ProductVariant::firstOrCreate([
                        'product_id' => $product->id,
                        'sku' => strtoupper(str_slug($name)) . "-{$color}-{$size}",
                        'color' => $color,
                        'size' => $size,
                        'weight' => rand(100, 1000) . 'g',
                        'stock' => rand(10, 100),
                    ]);
                }
            }
        }

        $this->command->info('Production seeder completed successfully!');
    }
}
