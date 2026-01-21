# Full-Stack Modernization: Step-by-Step Implementation Guide

## Quick Start (Week 1)

### Step 1: Initialize Monorepo
```bash
cd /path/to/nicolatetcholdiwsconsole-modern
git init
git config user.email "dev@nicolatetcholdiwsconsole.com"
git config user.name "Development Team"

# Create structure
mkdir -p apps/{api,admin,shop} packages/types .github/workflows
```

### Step 2: Setup pnpm Workspace
**pnpm-workspace.yaml** (root)
```yaml
packages:
  - 'apps/*'
  - 'packages/*'
```

**package.json** (root)
```json
{
  "private": true,
  "name": "nicolatetcholdiwsconsole-modern",
  "version": "1.0.0",
  "scripts": {
    "dev": "pnpm -r --parallel dev",
    "build": "pnpm -r build",
    "test": "pnpm -r test",
    "lint": "pnpm -r lint",
    "type-check": "pnpm -r type-check",
    "db:migrate": "cd apps/api && php artisan migrate",
    "db:seed": "cd apps/api && php artisan db:seed",
    "db:fresh": "cd apps/api && php artisan migrate:fresh --seed"
  },
  "devDependencies": {
    "turbo": "^1.10.0",
    "prettier": "^3.1.0"
  }
}
```

### Step 3: Create Laravel 11 API
```bash
cd apps/api

# Initialize with Laravel 11
composer create-project laravel/laravel . --no-interaction

# Install additional packages
composer require laravel/sanctum spatie/laravel-permission \
  laravel/tinker barryvdh/laravel-debugbar phpstan/phpstan

# Setup environment
cp .env.example .env
php artisan key:generate
```

**apps/api/.env**
```bash
APP_NAME="Nicola Tetcholdiwsconsole API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nicolatetcholdiwsconsole
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=database
SESSION_DRIVER=cookie

SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173,localhost:5174
CORS_ALLOWED_ORIGINS="http://localhost:3000,http://localhost:5173,http://localhost:5174"
```

### Step 4: Create Vue 3 Admin Dashboard
```bash
cd apps/admin

# Create Vite + Vue 3 project
npm create vite@latest . -- --template vue-ts
rm -rf node_modules package-lock.json

# Install with pnpm
pnpm install

# Add dependencies
pnpm add -D \
  @vitejs/plugin-vue \
  typescript \
  vue-tsc \
  tailwindcss postcss autoprefixer \
  eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin \
  prettier

pnpm add \
  vue vue-router pinia axios @headlessui/vue @heroicons/vue
```

### Step 5: Create Vue 3 Shop (Customer Frontend)
```bash
cd apps/shop

# Same as admin setup
npm create vite@latest . -- --template vue-ts
rm -rf node_modules package-lock.json
pnpm install
pnpm add -D @vitejs/plugin-vue typescript vue-tsc tailwindcss postcss autoprefixer
pnpm add vue vue-router pinia axios @headlessui/vue @heroicons/vue
```

### Step 6: Create Shared Types Package
**packages/types/package.json**
```json
{
  "name": "@nicolatetcholdiwsconsole/types",
  "version": "1.0.0",
  "main": "index.ts",
  "types": "index.ts",
  "files": ["*.ts"],
  "scripts": {
    "type-check": "tsc --noEmit"
  }
}
```

**packages/types/index.ts**
```typescript
export * from './models';
export * from './api';
export * from './dto';
```

---

## Week 2: Backend API Setup

### Step 1: Configure Laravel Sanctum & CORS
**apps/api/config/cors.php**
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

**apps/api/routes/api.php**
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ProductController,
    OrderController,
    CustomerController,
    CategoryController,
};

Route::middleware('api')->prefix('v1')->group(function () {
    // Public routes
    Route::get('health', fn() => response()->json(['status' => 'ok']));
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);

    // Auth
    Route::post('auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

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

### Step 2: Create Models with Relationships
```bash
cd apps/api

# Generate models with migrations
php artisan make:model Product -m
php artisan make:model Category -m
php artisan make:model ProductVariant -m
php artisan make:model Order -m
php artisan make:model OrderDetail -m
php artisan make:model Customer -m
```

**apps/api/app/Models/Product.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'sku', 'name', 'slug', 'description',
        'base_price', 'sale_price', 'category_id',
        'brand_id', 'is_active', 'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->where('stock_count', '>', 0);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function displayPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sale_price ?? $this->base_price,
        );
    }
}
```

### Step 3: Create API Controllers
```bash
mkdir -p apps/api/app/Http/Controllers/Api
```

**apps/api/app/Http/Controllers/Api/ApiController.php**
```php
<?php

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

**apps/api/app/Http/Controllers/Api/ProductController.php**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends ApiController
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with('category', 'variants')
            ->paginate(20);

        return $this->paginated($products);
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);
        $product = Product::create($request->validated());
        return $this->success(
            new ProductResource($product),
            'Product created',
            201
        );
    }

    public function show(Product $product)
    {
        return $this->success(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);
        $product->update($request->validated());
        return $this->success(new ProductResource($product));
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return $this->success(null, 'Product deleted');
    }
}
```

### Step 4: Create Form Requests
```bash
php artisan make:request StoreProductRequest
php artisan make:request UpdateProductRequest
```

**apps/api/app/Http/Requests/StoreProductRequest.php**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasPermissionTo('create products') ?? false;
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
        ];
    }
}
```

### Step 5: Create API Resources
```bash
php artisan make:resource ProductResource
```

**apps/api/app/Http/Resources/ProductResource.php**
```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'base_price' => (float) $this->base_price,
            'sale_price' => (float) $this->sale_price,
            'display_price' => (float) $this->display_price,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'variants' => $this->whenLoaded('variants'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

---

## Week 3: Frontend Setup

### Step 1: Configure Vite & TypeScript
**apps/admin/vite.config.ts**
```typescript
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath } from 'node:url'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      }
    }
  }
})
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
  "include": ["src/**/*.ts", "src/**/*.vue"],
  "references": [{ "path": "./tsconfig.node.json" }]
}
```

### Step 2: Setup Tailwind CSS
```bash
cd apps/admin
pnpm add -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

**tailwind.config.js**
```javascript
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

**src/style.css**
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### Step 3: Create API Client
**apps/admin/src/api/client.ts**
```typescript
import axios from 'axios'

const client = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  headers: {
    'Content-Type': 'application/json',
  },
})

// Add token to requests
client.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Handle 401 errors
client.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default client
```

### Step 4: Create Pinia Store
```bash
pnpm add pinia
```

**apps/admin/src/stores/auth.ts**
```typescript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import client from '@/api/client'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref(null)

  const isAuthenticated = computed(() => !!token.value)

  const login = async (email: string, password: string) => {
    const response = await client.post('/auth/login', { email, password })
    token.value = response.data.data.token
    user.value = response.data.data.user
    localStorage.setItem('token', token.value)
  }

  const logout = async () => {
    await client.post('/auth/logout')
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  return {
    token: computed(() => token.value),
    user: computed(() => user.value),
    isAuthenticated,
    login,
    logout,
  }
})
```

### Step 5: Setup Router
**apps/admin/src/router/index.ts**
```typescript
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Dashboard from '@/pages/Dashboard.vue'
import Login from '@/pages/Login.vue'

const routes = [
  {
    path: '/login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    path: '/',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else {
    next()
  }
})

export default router
```

---

## Docker Local Development

**docker-compose.yml** (root)
```yaml
version: '3.8'

services:
  app:
    image: php:8.2-fpm
    working_dir: /app
    volumes:
      - ./apps/api:/app
    environment:
      DB_CONNECTION: mysql
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: nicolatetcholdiwsconsole
      DB_USERNAME: root
      DB_PASSWORD: root
    depends_on:
      - db
      - redis

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: nicolatetcholdiwsconsole
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"

  api:
    image: nginx:alpine
    ports:
      - "8000:80"
    volumes:
      - ./apps/api/public:/app/public
    depends_on:
      - app

volumes:
  db_data:
```

**Quick Start**
```bash
docker-compose up -d
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

---

## Testing Setup

### Backend Tests
```bash
cd apps/api

# Generate test file
php artisan make:test ProductControllerTest --feature

# Run tests
php artisan test

# With coverage
php artisan test --coverage
```

### Frontend Tests
```bash
cd apps/admin

# Install testing dependencies
pnpm add -D vitest @testing-library/vue happy-dom

# Run tests
pnpm test
```

---

## Verification Checklist

- [ ] API health check: `curl http://localhost:8000/api/v1/health`
- [ ] Admin dashboard loads: `http://localhost:5173`
- [ ] Shop loads: `http://localhost:5174`
- [ ] Database migrated
- [ ] Types compile with no errors
- [ ] All tests passing
- [ ] No console errors in browser

