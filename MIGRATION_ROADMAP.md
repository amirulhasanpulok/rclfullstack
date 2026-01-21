# Full-Stack Modern Modernization Roadmap
## Nicola Tetcholdiwsconsole E-Commerce Platform

**Target**: Production-ready modern architecture with separated frontend/backend, type safety, automated testing, and CI/CD.

---

## Executive Summary

### Current State (Legacy)
- **Frontend**: Blade templates + Bootstrap 5 (server-rendered)
- **Backend**: Laravel 9, mixed web/API routes, permission checks scattered
- **Database**: Functional but denormalized (e.g., `ProductVariable` instead of variants table)
- **Deployment**: Manual, no CI/CD
- **Architecture**: Monolithic (frontend + backend tightly coupled)

### Target State (Modern)
- **Frontend**: Vue 3 + TypeScript + Tailwind CSS (SPA/client-rendered)
- **Backend**: Laravel 11, pure REST API, centralized permission gates
- **Database**: Normalized schema with proper indexing
- **Deployment**: GitHub Actions CI/CD with automated testing
- **Architecture**: Monorepo with separated concerns, type-safe across stack
- **DevX**: Hot reload, TypeScript everywhere, automated formatting

### Timeline: 12-16 weeks (aggressive) / 24+ weeks (careful)

---

## Phase 1: Foundation Setup (Weeks 1-3)

### 1.1 Project Structure - New Monorepo Layout
```
nicolatetcholdiwsconsole/
├── apps/
│   ├── api/                          # Laravel 11 API backend
│   │   ├── app/
│   │   ├── config/
│   │   ├── database/
│   │   ├── tests/
│   │   ├── .env.example
│   │   ├── composer.json
│   │   └── phpunit.xml
│   │
│   ├── admin/                        # Vue 3 admin dashboard
│   │   ├── src/
│   │   │   ├── pages/
│   │   │   ├── components/
│   │   │   ├── stores/               # Pinia state management
│   │   │   ├── services/             # API clients
│   │   │   ├── types/                # TypeScript interfaces
│   │   │   └── App.vue
│   │   ├── package.json
│   │   ├── vite.config.ts
│   │   └── tsconfig.json
│   │
│   └── shop/                         # Vue 3 customer frontend
│       ├── src/
│       ├── package.json
│       ├── vite.config.ts
│       └── tsconfig.json
│
├── packages/                         # Shared code
│   └── types/                        # Shared TypeScript types
│       ├── api.ts
│       ├── models.ts
│       └── package.json
│
├── .github/
│   ├── workflows/
│   │   ├── api-test.yml
│   │   ├── api-lint.yml
│   │   ├── admin-test.yml
│   │   └── deploy.yml
│   └── copilot-instructions.md
│
├── docker-compose.yml                # Local dev environment
├── pnpm-workspace.yaml               # Monorepo config
├── turbo.json                        # Build orchestration
└── README.md
```

### 1.2 Setup Commands
```bash
# Prerequisites
node --version  # v18+
php -v          # 8.2+
composer --version

# Create monorepo root
mkdir nicolatetcholdiwsconsole-modern
cd nicolatetcholdiwsconsole-modern
git init

# Initialize monorepo
npm install -g pnpm
pnpm init

# Create workspace structure
mkdir -p apps/{api,admin,shop} packages/types

# Setup API (Laravel 11)
cd apps/api
composer create-project laravel/laravel . --no-interaction
php artisan key:generate

# Setup Admin (Vue 3)
cd ../admin
npm create vite@latest . -- --template vue-ts
pnpm install

# Setup Shop (Vue 3)
cd ../shop
npm create vite@latest . -- --template vue-ts
pnpm install
```

### 1.3 Environment Configuration
**apps/api/.env.example**
```bash
APP_NAME="Nicola Tetcholdiwsconsole API"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000
LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nicolatetcholdiwsconsole
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173
CORS_ALLOWED_ORIGINS="http://localhost:5173,http://localhost:5174"

QUEUE_CONNECTION=database
CACHE_DRIVER=redis
SESSION_DRIVER=cookie
```

**docker-compose.yml** (Local development)
```yaml
version: '3.8'

services:
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: nicolatetcholdiwsconsole
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"

  mailhog:
    image: mailhog/mailhog
    ports:
      - "1025:1025"
      - "8025:8025"

volumes:
  mysql_data:
```

---

## Phase 2: Backend Modernization (Weeks 4-6)

### 2.1 Laravel 11 API Setup

**Key Upgrades from Laravel 9 → 11:**
- ✅ Route attributes (`#[Route(...)]` instead of `Route::get()`)
- ✅ Service layer pattern (move business logic out of controllers)
- ✅ Data Transfer Objects (DTOs)
- ✅ API resources (transform models to consistent JSON)
- ✅ Stronger type hints
- ✅ Env validation

**apps/api/config/app.php**
```php
<?php

return [
    'name' => env('APP_NAME', 'Nicola Tetcholdiwsconsole'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'providers' => [
        // ... standard providers
        App\Providers\ApiServiceProvider::class,
    ],
];
```

### 2.2 New Model & Database Schema

**Migration: Create Modern Schema**
```php
// database/migrations/2024_01_XX_create_products_table.php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('sku')->unique();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description');
    $table->unsignedDecimal('base_price', 10, 2);
    $table->unsignedDecimal('sale_price', 10, 2)->nullable();
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('cascade');
    $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
    $table->boolean('is_active')->default(true);
    $table->boolean('is_featured')->default(false);
    $table->integer('stock_count')->default(0);
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['category_id', 'is_active']);
    $table->index(['brand_id', 'is_active']);
    $table->fullText(['name', 'description']); // MySQL full-text search
});

// Product variants (replaces ProductVariable)
Schema::create('product_variants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->string('sku')->unique();
    $table->string('color')->nullable();
    $table->string('size')->nullable();
    $table->string('weight')->nullable();
    $table->integer('stock_count');
    $table->decimal('price_adjustment', 8, 2)->default(0);
    $table->timestamps();
    
    $table->unique(['product_id', 'color', 'size', 'weight']);
});
```

**Updated Product Model**
```php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo, HasOne};
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'sku', 'name', 'slug', 'description', 'base_price',
        'sale_price', 'category_id', 'brand_id', 'is_active', 'is_featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->where('stock_count', '>', 0);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Computed attributes
    protected function displayPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sale_price ?? $this->base_price,
        );
    }

    // Route binding by slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

### 2.3 API Controller Architecture

**Base Controller with Consistent Responses**
```php
// app/Http/Controllers/Api/ApiController.php
namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;

class ApiController
{
    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function paginated(AbstractPaginator $paginator): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}
```

**Resource-based Controllers with Routes**
```php
// app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Routing\Attributes\Route;

class ProductController extends ApiController
{
    #[Route('GET', 'products')]
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with('category', 'variants', 'images')
            ->paginate(20);

        return $this->paginated($products);
    }

    #[Route('POST', 'products')]
    public function store(StoreProductRequest $request)
    {
        // Authorization checked via gates/policies
        $this->authorize('create', Product::class);

        $product = Product::create($request->validated());

        return $this->success(
            new ProductResource($product),
            'Product created successfully',
            201
        );
    }

    #[Route('GET', 'products/{product}')]
    public function show(Product $product)
    {
        return $this->success(new ProductResource($product));
    }

    #[Route('PUT', 'products/{product}')]
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return $this->success(new ProductResource($product));
    }

    #[Route('DELETE', 'products/{product}')]
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return $this->success(null, 'Product deleted successfully');
    }
}
```

**API Routes - Clean Structure**
```php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ProductController,
    OrderController,
    CustomerController,
    CategoryController,
};

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);

    // Auth routes
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'profile']);

        // Customer routes
        Route::apiResource('customers', CustomerController::class)->only('index', 'show', 'update');
        Route::get('customers/{customer}/orders', [OrderController::class, 'customerOrders']);

        // Admin routes
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('products', ProductController::class);
            Route::apiResource('orders', OrderController::class);
            Route::apiResource('categories', CategoryController::class);
        });
    });
});
```

### 2.4 Service Layer Pattern

```php
// app/Services/ProductService.php
namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    public function getActiveProducts(int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->getActiveWithRelations($perPage);
    }

    public function createProduct(array $data): Product
    {
        // Validate business rules
        if (Product::where('sku', $data['sku'])->exists()) {
            throw new \Exception('SKU already exists');
        }

        return $this->repository->create($data);
    }

    public function updateStock(Product $product, int $quantity): bool
    {
        return $this->repository->updateStock($product, $quantity);
    }
}
```

### 2.5 Type-Safe Request Validation

```php
// app/Http/Requests/StoreProductRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasPermissionTo('create products');
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:base_price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
```

---

## Phase 3: Frontend Modernization (Weeks 7-10)

### 3.1 Vue 3 + TypeScript Setup

**apps/admin/package.json**
```json
{
  "name": "@nicolatetcholdiwsconsole/admin",
  "version": "1.0.0",
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview",
    "lint": "eslint . --fix",
    "type-check": "vue-tsc --noEmit"
  },
  "dependencies": {
    "vue": "^3.4.0",
    "vue-router": "^4.3.0",
    "pinia": "^2.1.0",
    "axios": "^1.6.0",
    "@headlessui/vue": "^1.7.0",
    "@heroicons/vue": "^2.0.0"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.0.0",
    "typescript": "^5.3.0",
    "vue-tsc": "^1.8.0",
    "tailwindcss": "^3.4.0",
    "postcss": "^8.4.0",
    "autoprefixer": "^10.4.0"
  }
}
```

**apps/admin/tsconfig.json**
```json
{
  "compilerOptions": {
    "target": "ES2020",
    "useDefineForClassFields": true,
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "module": "ESNext",
    "skipLibCheck": true,
    "strict": true,
    "esModuleInterop": true,
    "moduleResolution": "bundler",
    "allowImportingTsExtensions": true,
    "resolveJsonModule": true,
    "isolatedModules": true,
    "noEmit": true,
    "jsx": "preserve",
    "baseUrl": ".",
    "paths": {
      "@/*": ["./src/*"]
    }
  },
  "include": ["src/**/*.ts", "src/**/*.d.ts", "src/**/*.tsx", "src/**/*.vue"],
  "references": [{ "path": "./tsconfig.node.json" }]
}
```

### 3.2 Folder Structure

**apps/admin/src/**
```
src/
├── api/
│   ├── client.ts                    # Axios instance with interceptors
│   ├── products.ts                  # Product API calls
│   ├── orders.ts
│   ├── auth.ts
│   └── types.ts                     # API response types
│
├── stores/                          # Pinia state management
│   ├── auth.ts                      # Auth state
│   ├── products.ts                  # Product state
│   └── ui.ts                        # UI state (notifications, loading)
│
├── pages/
│   ├── Dashboard.vue
│   ├── Products/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   └── Edit.vue
│   ├── Orders/
│   │   ├── Index.vue
│   │   └── Detail.vue
│   └── Settings/
│       └── Index.vue
│
├── components/
│   ├── Navigation/
│   │   ├── Sidebar.vue
│   │   └── TopBar.vue
│   ├── DataTable/
│   │   ├── Table.vue
│   │   ├── Pagination.vue
│   │   └── Filters.vue
│   ├── Forms/
│   │   ├── ProductForm.vue
│   │   └── FormInput.vue
│   └── common/
│       ├── Modal.vue
│       ├── Notification.vue
│       └── LoadingSpinner.vue
│
├── types/
│   ├── index.ts                     # Shared types
│   ├── models.ts
│   └── api.ts
│
├── router/
│   └── index.ts
│
├── App.vue
└── main.ts
```

### 3.3 API Client with Interceptors

```typescript
// apps/admin/src/api/client.ts
import axios, { AxiosInstance, InternalAxiosRequestConfig } from 'axios';
import { useAuthStore } from '@/stores/auth';

const client: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor - add auth token
client.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const authStore = useAuthStore();
  if (authStore.token) {
    config.headers.Authorization = `Bearer ${authStore.token}`;
  }
  return config;
});

// Response interceptor - handle errors
client.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      const authStore = useAuthStore();
      authStore.logout();
    }
    return Promise.reject(error);
  }
);

export default client;
```

**API Service Example**
```typescript
// apps/admin/src/api/products.ts
import client from './client';
import type { Product, CreateProductDTO, UpdateProductDTO } from '@/types/models';

export const productsAPI = {
  list: (page = 1, perPage = 20) =>
    client.get<{ data: Product[]; pagination: any }>('/products', {
      params: { page, per_page: perPage },
    }),

  get: (id: number) =>
    client.get<{ data: Product }>(`/products/${id}`),

  create: (data: CreateProductDTO) =>
    client.post<{ data: Product }>('/products', data),

  update: (id: number, data: UpdateProductDTO) =>
    client.put<{ data: Product }>(`/products/${id}`, data),

  delete: (id: number) =>
    client.delete(`/products/${id}`),

  search: (query: string) =>
    client.get<{ data: Product[] }>('/products/search', {
      params: { q: query },
    }),
};
```

### 3.4 Pinia State Management

```typescript
// apps/admin/src/stores/products.ts
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { productsAPI } from '@/api/products';
import type { Product } from '@/types/models';

export const useProductStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  const fetchProducts = async (page = 1) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsAPI.list(page);
      products.value = response.data.data;
    } catch (err) {
      error.value = 'Failed to fetch products';
    } finally {
      loading.value = false;
    }
  };

  const createProduct = async (data: any) => {
    try {
      const response = await productsAPI.create(data);
      products.value.unshift(response.data.data);
      return response.data.data;
    } catch (err) {
      throw new Error('Failed to create product');
    }
  };

  return {
    products: computed(() => products.value),
    loading: computed(() => loading.value),
    error: computed(() => error.value),
    fetchProducts,
    createProduct,
  };
});
```

### 3.5 TypeScript Types (Shared)

```typescript
// packages/types/models.ts
export interface Product {
  id: number;
  sku: string;
  name: string;
  slug: string;
  description: string;
  base_price: number;
  sale_price?: number;
  display_price: number;
  category_id: number;
  brand_id?: number;
  is_active: boolean;
  is_featured: boolean;
  stock_count: number;
  variants: ProductVariant[];
  images: ProductImage[];
  created_at: string;
  updated_at: string;
}

export interface ProductVariant {
  id: number;
  sku: string;
  color?: string;
  size?: string;
  weight?: string;
  stock_count: number;
  price_adjustment: number;
}

export interface CreateProductDTO {
  sku: string;
  name: string;
  slug: string;
  description: string;
  base_price: number;
  sale_price?: number;
  category_id: number;
  brand_id?: number;
}

export type UpdateProductDTO = Partial<CreateProductDTO>;
```

---

## Phase 4: Testing & Quality Assurance (Weeks 11-12)

### 4.1 Backend Testing

```php
// tests/Feature/Api/ProductControllerTest.php
namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\{Product, Category};
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        Product::factory(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'slug', 'base_price']
                ],
                'pagination'
            ]);
    }

    public function test_admin_can_create_product(): void
    {
        $user = $this->createAdminUser();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/v1/products', [
                'sku' => 'TEST-001',
                'name' => 'Test Product',
                'slug' => 'test-product',
                'description' => 'Test',
                'base_price' => 100,
                'category_id' => $category->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Product');

        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-001',
            'name' => 'Test Product',
        ]);
    }
}
```

### 4.2 Frontend Testing

```typescript
// apps/admin/src/__tests__/stores/products.test.ts
import { setActivePinia, createPinia } from 'pinia';
import { useProductStore } from '@/stores/products';
import { vi } from 'vitest';
import * as productsAPI from '@/api/products';

vi.mock('@/api/products');

describe('Product Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
  });

  it('fetches products', async () => {
    const mockProducts = [
      { id: 1, name: 'Product 1', sku: 'P001' },
    ];
    vi.mocked(productsAPI.productsAPI.list).mockResolvedValue({
      data: { data: mockProducts },
    } as any);

    const store = useProductStore();
    await store.fetchProducts();

    expect(store.products).toEqual(mockProducts);
    expect(store.loading).toBe(false);
  });
});
```

---

## Phase 5: Database Migration (Weeks 13-14)

### 5.1 Data Migration Strategy

```php
// database/migrations/2024_01_XX_migrate_legacy_products.php
Schema::create('product_migration_logs', function (Blueprint $table) {
    $table->id();
    $table->integer('legacy_id');
    $table->integer('new_id');
    $table->boolean('success');
    $table->text('errors')->nullable();
    $table->timestamps();
});

// In migration
$legacyProducts = DB::connection('legacy')
    ->table('products')
    ->get();

foreach ($legacyProducts as $old) {
    try {
        $new = Product::create([
            'sku' => $old->sku ?? 'LEGACY-' . $old->id,
            'name' => $old->name,
            'slug' => Str::slug($old->name),
            'description' => $old->description ?? '',
            'base_price' => $old->old_price ?? 0,
            'sale_price' => $old->new_price,
            'category_id' => $this->migrateCategory($old->category_id),
            'brand_id' => $old->brand_id,
            'is_active' => $old->status == 1,
        ]);

        Log::create([
            'legacy_id' => $old->id,
            'new_id' => $new->id,
            'success' => true,
        ]);
    } catch (\Exception $e) {
        Log::create([
            'legacy_id' => $old->id,
            'success' => false,
            'errors' => $e->getMessage(),
        ]);
    }
}
```

---

## Phase 6: CI/CD & Deployment (Weeks 15-16)

### 6.1 GitHub Actions Workflows

**.github/workflows/api-test.yml**
```yaml
name: API Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: test
          MYSQL_ROOT_PASSWORD: root
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
        ports:
          - 3306:3306

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo_mysql

      - name: Install dependencies
        run: cd apps/api && composer install

      - name: Setup environment
        run: |
          cd apps/api
          cp .env.example .env
          php artisan key:generate

      - name: Run tests
        run: cd apps/api && php artisan test

      - name: Run static analysis
        run: cd apps/api && ./vendor/bin/phpstan analyse app --level=max
```

**.github/workflows/admin-test.yml**
```yaml
name: Admin Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v3

      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          cache: 'pnpm'

      - name: Install dependencies
        run: pnpm install

      - name: Type check
        run: cd apps/admin && pnpm type-check

      - name: Lint
        run: cd apps/admin && pnpm lint

      - name: Run tests
        run: cd apps/admin && pnpm test
```

### 6.2 Docker Deployment

**Dockerfile.api**
```dockerfile
FROM php:8.2-fpm

WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    mysql-client \
    && docker-php-ext-install pdo_mysql

# Copy app
COPY apps/api /app

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Run migrations & start
CMD ["sh", "-c", "php artisan migrate --force && php-fpm"]
```

**docker-compose.prod.yml**
```yaml
version: '3.8'

services:
  api:
    build:
      context: .
      dockerfile: Dockerfile.api
    environment:
      DB_HOST: db
      REDIS_HOST: redis
    depends_on:
      - db
      - redis
    ports:
      - "8000:9000"

  admin:
    build:
      context: apps/admin
      dockerfile: Dockerfile
    ports:
      - "3000:80"
    environment:
      VITE_API_URL: http://api:8000/api/v1

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: nicolatetcholdiwsconsole
      MYSQL_ROOT_PASSWORD: "${DB_PASSWORD}"
    volumes:
      - db_data:/var/lib/mysql

  redis:
    image: redis:7-alpine

volumes:
  db_data:
```

---

## Phase 7: Documentation & DevOps (Weeks 17-20)

### 7.1 Updated README

**.github/copilot-instructions.md** (Updated)
```markdown
# Copilot Instructions - Modern Stack

## Modern Project Structure

### API (Laravel 11)
- **Location**: `apps/api/`
- **Type**: REST API with pure resource controllers
- **Authentication**: Laravel Sanctum
- **Pattern**: Service Layer + Repository Pattern
- **Types**: Request/Resource for type safety

### Admin Dashboard (Vue 3 + TypeScript)
- **Location**: `apps/admin/`
- **Type**: Single Page Application
- **State**: Pinia store
- **Styling**: Tailwind CSS
- **Components**: Headless UI

### Customer Frontend (Vue 3 + TypeScript)
- **Location**: `apps/shop/`
- **Type**: E-commerce storefront
- **Pattern**: Component-driven

### Shared Types
- **Location**: `packages/types/`
- **Purpose**: Single source of truth for DTO interfaces

## Development Workflow

### Local Setup
\`\`\`bash
pnpm install          # Install all workspaces
pnpm -r dev           # Start all dev servers
docker-compose up -d  # Start services (MySQL, Redis, Mailhog)
\`\`\`

### Database
\`\`\`bash
cd apps/api
php artisan migrate
php artisan seed:all
\`\`\`

### Code Quality
\`\`\`bash
# API
cd apps/api && ./vendor/bin/phpstan analyse app --level=max
cd apps/api && ./vendor/bin/pint

# Admin
cd apps/admin && pnpm lint && pnpm type-check
\`\`\`

## Key Patterns

### API Response Format
All endpoints return standardized JSON:
\`\`\`json
{
  "success": true,
  "message": "...",
  "data": { ... }
}
\`\`\`

### Frontend API Calls
Use typed API service from `src/api/` with interceptors.

### Authorization
- Backend: Gate/Policy + middleware
- Frontend: Derived from API responses
\`\`\`
```

### 7.2 Deployment Checklist

```markdown
# Deployment Checklist

## Pre-Deployment
- [ ] All tests passing
- [ ] Type checks passing (frontend & backend)
- [ ] No linting errors
- [ ] Database migrations reviewed
- [ ] Environment variables documented
- [ ] Security audit completed

## Deployment Steps
1. Build API: `cd apps/api && composer install --no-dev`
2. Build Admin: `cd apps/admin && pnpm build`
3. Run migrations: `php artisan migrate --force`
4. Clear caches: `php artisan config:clear && cache:clear`
5. Restart services

## Post-Deployment
- [ ] API health check: `GET /api/v1/health`
- [ ] Admin accessible
- [ ] Customer site loads
- [ ] Logs checked for errors
- [ ] Payment gateway working
```

---

## Migration Commands Reference

### Initial Setup
```bash
# Create monorepo
git init nicolatetcholdiwsconsole-modern
cd nicolatetcholdiwsconsole-modern

# Setup workspaces
pnpm init -y
echo '{"packages": ["apps/*", "packages/*"]}' > pnpm-workspace.yaml

# Create Laravel API
cd apps/api
composer create-project laravel/laravel . --no-interaction

# Setup Vue Admin
cd ../admin
npm create vite@latest . -- --template vue-ts
pnpm install

# Setup Vue Shop
cd ../shop
npm create vite@latest . -- --template vue-ts
pnpm install
```

### During Migration
```bash
# Test API
php artisan test

# Build frontend
cd apps/admin && pnpm build

# Type check
cd apps/admin && vue-tsc --noEmit

# Migrate data
php artisan migrate
php artisan command:migrate-legacy-data
```

### Verification
```bash
# Health checks
curl http://localhost:8000/api/v1/health
curl http://localhost:3000

# Database
php artisan tinker
>>> Product::count()
>>> Order::with('orderdetails')->first()
```

---

## Rollback Plan

If major issues occur:

1. **Stop new services**: Stop Docker containers
2. **Restore data**: Use backup MySQL snapshots
3. **Revert code**: Git rollback to stable commit
4. **Verify**: Run tests & health checks
5. **Document**: Add to incident log

---

## Timeline Summary

| Phase | Duration | Deliverables |
|-------|----------|--------------|
| 1: Foundation | 3 weeks | Monorepo structure, env setup |
| 2: Backend | 3 weeks | Laravel 11 API, services, tests |
| 3: Frontend | 4 weeks | Vue 3 admin/shop, TypeScript |
| 4: Testing | 2 weeks | 80%+ coverage, CI/CD |
| 5: Migration | 2 weeks | Data migration, validation |
| 6: DevOps | 2 weeks | Docker, deployment scripts |
| 7: Documentation | 4 weeks | API docs, runbooks |
| **Total** | **~20 weeks** | **Production-ready** |

---

## Success Metrics

- ✅ 100% test coverage (critical paths)
- ✅ Type coverage > 90%
- ✅ Zero console errors
- ✅ API response time < 200ms (p95)
- ✅ Frontend load time < 3s
- ✅ Zero security vulnerabilities
- ✅ CI/CD fully automated

