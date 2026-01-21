# Copilot Instructions for Nicola Tetcholdiwsconsole

## Current State (2026)
**Traditional Laravel monolithic e-commerce platform** – actively modernizing to monorepo with separate Vue 3 frontends.

### Current Stack
- **Backend**: Laravel 9.19, PHP 8.1+, MVC with Blade + REST API endpoints
- **Database**: MySQL with legacy schema (ProductVariable, multiple color/size tables)
- **Auth**: Laravel Sanctum + Spatie Permissions (roles/permissions in DB)
- **Payment Integration**: Shurjopay v2, BKash (webhooks in `ShurjopayControllers.php`)
- **Testing**: PHPUnit 9.5 for backend
- **Frontend**: Blade templates (legacy) + basic Vite + Vue/Bootstrap (being replaced)

### Modernization Plan
See `MIGRATION_ROADMAP.md` (850 lines) for phased transition to:
- **Backend**: Laravel 11, pure REST API under `apps/api/`
- **Admin**: Vue 3 + TypeScript dashboard under `apps/admin/`
- **Shop**: Vue 3 + TypeScript storefront under `apps/shop/`
- **Monorepo**: pnpm workspaces + Turbo orchestration

---

## Project Structure (Current)

```
nicolatetcholdiwsconsole-main/
├── app/                        # Laravel application core
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers (31 files)
│   │   │   ├── Frontend/       # Customer-facing controllers
│   │   │   ├── Api/            # API endpoints (FrontendController.php)
│   │   │   └── Auth/
│   │   ├── Middleware/         # Custom middleware
│   │   ├── Kernel.php          # HTTP/console kernel
│   │   └── Requests/           # (limited; mostly in controllers)
│   ├── Models/                 # 27 models for database entities
│   │   ├── Product.php         # Route key: 'slug'
│   │   ├── ProductVariable.php # Legacy: combines color/size/weight
│   │   ├── Order.php
│   │   ├── OrderStatus.php     # Workflow tracking
│   │   ├── User.php
│   │   └── ...
│   ├── Providers/              # Service providers
│   └── Console/
├── routes/
│   ├── web.php                 # Admin panel + frontend (574 lines)
│   ├── api.php                 # REST API (v1 namespace, minimal)
│   ├── admin.php               # Admin routes group
│   └── channels.php
├── database/
│   ├── migrations/             # Schema definitions
│   ├── seeders/                # Data seeding
│   └── factories/
├── config/                     # Laravel config files
│   ├── sanctum.php             # Token auth config
│   ├── permission.php          # Spatie permissions
│   ├── shurjopay.php           # Payment gateway
│   └── ...
├── storage/
│   ├── logs/                   # Error/info logs
│   └── ...
├── tests/                      # PHPUnit tests
│   ├── Feature/
│   └── Unit/
├── public/                     # Web root
│   ├── backEnd/                # Admin assets
│   ├── frontEnd/               # Customer assets
│   └── uploads/                # User uploads
├── resources/
│   ├── views/                  # Blade templates
│   ├── css/
│   └── js/                     # Bootstrap Vue code
├── composer.json               # PHP dependencies
├── package.json                # Node dependencies (minimal)
├── .env.example                # Environment template
├── MIGRATION_ROADMAP.md        # 850-line modernization guide
├── IMPLEMENTATION_GUIDE.md     # Week-by-week implementation
└── docker-compose.modern.yml   # Future monorepo setup
```

### Planned Monorepo Structure (Target)
See `MIGRATION_ROADMAP.md` for full migration plan:
```
apps/
├── api/                    # Migrated Laravel 11 API
├── admin/                  # Vue 3 admin dashboard (to build)
└── shop/                   # Vue 3 customer storefront (to build)
packages/
└── types/                  # Shared TypeScript types
```

---

## Architecture Essentials

### Current MVC Pattern
- **Routes** (`routes/web.php` 574 lines): Admin panel + frontend; no resource routing yet
- **Controllers** (`app/Http/Controllers/Admin/*`): Model manipulation, authorization mixed in
- **Models** (`app/Models/*` 27 files): Blade-friendly; relationships use `hasOne()`, `hasMany()`
- **Views** (`resources/views/`): Blade templates; legacy form submission + some Vue

### Key Entity Schema (Legacy, Pre-Normalization)
- **Product**: `id, name, slug, base_price, description, category_id, brand_id, is_active`
- **ProductVariable**: Legacy denormalized table - combines `sku, color, size, weight, stock` in one row
  - Problem: Can't easily query "all reds" or validate color+size combinations
  - Migration needed: Split into `ProductVariants` + pivot tables `ProductColors`, `ProductSizes`
- **Order**: `id, customer_id, total_price, status, tracking_id, courier` (multi-row OrderDetails)
- **OrderStatus**: Enum-like table tracking workflow (pending → processing → shipped → delivered)
- **User + permissions**: Via Spatie `roles` and `permissions` tables

### Actual Data Flow (Current)
1. **Admin Routes** → Admin Controllers (ProductController, etc.)
2. **Form Submission** → Controller method (store/update) with validation inline
3. **Model.save()** → Database
4. **Response** → Blade template render OR JSON (inconsistent)
5. **Frontend API** → `routes/api.php` → `Api/FrontendController.php` (minimal, customer-facing only)

### Authorization Pattern (Current)
- **Spatie Permissions**: `$user->hasRole('admin')`, `$user->hasPermissionTo('edit products')`
- **Location**: Inline in controllers, not centralized (⚠️ scattered checks)
- **Target**: Move to Gates/Policies after modernization

---

## Developer Workflows

### Setup & Local Development (Current)
```bash
# Install PHP dependencies
composer install

# Install Node dependencies (minimal; Vite build only)
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start Laravel dev server
php artisan serve              # Runs on localhost:8000

# Build frontend assets with Vite
npm run dev                    # Watch mode for CSS/JS
```

### Running Tests
```bash
# Backend unit & feature tests
php artisan test

# With PHPUnit directly
./vendor/bin/phpunit

# Specific test file
php artisan test tests/Feature/ProductControllerTest.php
```

### Debugging
```bash
# Use Laravel Tinker for quick model testing
php artisan tinker
>>> Product::all()->first()
>>> User::with('roles')->get()

# Check Sanctum tokens
>>> Auth::guard('sanctum')->user()
```

### Database Changes
1. Create migration: `php artisan make:migration create_table_name`
2. Modify schema in `database/migrations/`
3. Add model in `app/Models/` with relationships + `$fillable`
4. Run: `php artisan migrate`
5. Test via: `php artisan tinker`

---

## Project-Specific Conventions

### Model Patterns (Current)
- **Route Key**: Products use slugs (`getRouteKeyName()` returns `'slug'`)
- **Guarded/Fillable**: Use `protected $guarded = []` (⚠️ fix to explicit `$fillable` during modernization)
- **Relationships**: Always use explicit `with()` in queries; filter stock: `.where('stock','>',0)`
- **Stock Check**: ProductVariable queries filter `.where('stock','>',0)` to exclude out-of-stock
- **Example**: 
  ```php
  public function variables()
  {
      return $this->hasMany('App\Models\ProductVariable')->where('stock','>',0);
  }
  ```

### Admin Controller Patterns
- **Location**: `/app/Http/Controllers/Admin/` (31 controllers for entity management)
- **Authorization**: Inline Spatie checks: `$user->hasRole('admin')` or `$user->hasPermissionTo('edit products')`
- **Validation**: Often inline in controller method; Form Requests underutilized
- **Response**: Mix of Blade redirect + JSON (inconsistent)
- **Example Path**: `Admin/ProductController.php` → `web.php` routes → Blade views

### Frontend API (Minimal)
- **Endpoint**: `routes/api.php` v1 namespace with `/api/v1/` prefix
- **Controller**: `App\Http\Controllers\Api\FrontendController.php` (single controller)
- **Methods**: `appconfig()`, `slider()`, `categorymenu()`, `hotdealproduct()`, etc.
- **Response**: JSON (inconsistent format; no Resource transformers)
- **Auth**: Basic Sanctum middleware available but minimal usage

### Payment Integration
- **Shurjopay**: `ShurjopayControllers.php` handles webhook callbacks
- **BKash**: Legacy support in `BkashController.php`
- **Flow**: Customer payment → Gateway callback → Webhook verification → Order creation
- **Order Model**: Tracks `tracking_id` and `courier` after successful payment
- **Config**: See `config/shurjopay.php`
- **Authorization**: `role:admin`, `can:create-products` for permission checks
- **CORS**: Configured for localhost:5173, localhost:5174
- **Throttling**: API rate limiting via `throttle:api`

### Permissions Architecture (Spatie)
- Roles/Permissions stored in `roles` and `permissions` tables
- Models use `HasRoles` trait
- Check access: `user->hasRole('admin')` or `user->hasPermissionTo('edit products')`
- Sync roles/permissions via seeders or admin API

---

## Integration Points & External Dependencies

### Payment Gateways
- **Shurjopay**: Via `shurjopayv2/laravel8` package; REST API integration
- **Bkash**: Legacy support in `BkashController.php`
- **Orders**: Created in database after successful payment; track via `tracking_id` and `courier` fields
- **Webhooks**: Handle payment callbacks with signature verification

### Third-Party APIs
- **Facebook Pixel**: Track conversions via `TagManagerController`
- **Google Tag Manager**: Analytics via `analytics` middleware
- **Courier APIs**: `Courierapi.php` model + `ShippingCharge` for delivery quotes
- **SMS/Email**: Use Laravel Mail & queue jobs for async delivery

### Frontend Integration (Legacy)
- **Blade Templates**: Mixed with Vue components in `resources/views/`
- **jQuery/Bootstrap**: Used alongside Vue 3 (being replaced in modernization)
- **Vite Build**: Minimal usage; full transition planned with new frontends

---

## File Organization Quick Reference (Current)
| Purpose | Location |
|---------|----------|
| Web routes | `routes/web.php` (574 lines, all routes) |
| API routes | `routes/api.php` (minimal, v1 namespace) |
| Admin controllers | `app/Http/Controllers/Admin/` (31 files) |
| API endpoint | `app/Http/Controllers/Api/FrontendController.php` |
| Models | `app/Models/` (27 files) |
| Database migrations | `database/migrations/` |
| Database seeders | `database/seeders/` |
| Blade views | `resources/views/` |
| JavaScript/Vue | `resources/js/` |
| Styles | `resources/css/` and `resources/sass/` |
| Config files | `config/` (sanctum, permission, shurjopay, etc.) |
| Tests | `tests/Feature/` and `tests/Unit/` |
| Public assets | `public/backEnd/`, `public/frontEnd/`, `public/uploads/` |

---

## Common Patterns to Reuse

### Product Query with Relationships
```php
Product::with('category', 'subcategory', 'childcategory', 'brand', 'images')
    ->where('is_active', 1)
    ->whereHas('variables', function($q) {
        $q->where('stock', '>', 0);
    })
    ->paginate(20);
```

### Model Relationships (Stock-Aware)
```php
public function variables()
{
    return $this->hasMany('App\Models\ProductVariable')->where('stock','>',0);
}

public function category()
{
    return $this->hasOne(Category::class, 'id', 'category_id')
        ->select('id','name','slug');
}
```

### Admin Route Registration Pattern
```php
// In routes/web.php - group routes with auth & admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    // ... 30+ more resource routes
});
```

### Pinia Store Example
```typescript
export const useProductStore = defineStore('products', () => {
  const products = ref<Product[]>([])
  const loading = ref(false)
  
  const fetchProducts = async () => {
    loading.value = true
    try {
      const res = await productsAPI.list()
      products.value = res.data.data
    } finally {
      loading.value = false
    }
  }
  
  return { products: computed(() => products.value), fetchProducts }
})
```

### Form Request Validation
```php
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
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
```

---

## Gotchas & Debugging Tips
1. **Route Binding**: Products route by slug; use `Product::find()` for IDs in API
2. **Stock Check**: Always filter variants with `.where('stock','>',0)`
3. **CORS Errors**: Check `CORS_ALLOWED_ORIGINS` in `config/cors.php`
4. **API Resources**: Minimal usage currently; inconsistent JSON format between endpoints
5. **Mass Assignment**: Use `$guarded = []` (current) → move to explicit `$fillable` during modernization
6. **Authorization**: Inline permission checks → centralize in Gates/Policies post-modernization
7. **Payment Webhooks**: Handle unsigned requests with signature verification in `ShurjopayControllers.php`

---

## What NOT to Do
- ❌ Don't add validation outside controllers (currently inline, but migrate to Form Requests)
- ❌ Don't scatter authorization checks; centralize in middleware or policies
- ❌ Don't use `$guarded = []`; explicitly define `$fillable`
- ❌ Don't forget to `.where('stock', '>', 0)` when querying product variants
- ❌ Don't commit without running `php artisan test`
- ❌ Don't hardcode role/permission names; use Spatie constants or sync with DB

---

## Quick Start for Common Tasks

### Add New Admin Page
1. Create controller in `app/Http/Controllers/Admin/`
2. Add routes to `routes/web.php` (use `Route::resource()` or custom methods)
3. Create Blade view in `resources/views/admin/` with form + JS
4. Add authorization check inline: `$this->authorize('edit', $model)` or `abort_unless(auth()->user()->hasRole('admin'), 403)`
5. Test manually in browser

### Add Product Attribute
1. Create migration: `php artisan make:migration add_column_to_products`
2. Update `Product` model `$guarded` (or add to $fillable during cleanup)
3. Update ProductController create/edit views
4. Run migration: `php artisan migrate`

### Fix Stock Query Issue
- Always use: `.with('variables')` then filter in model relationship definition
- Or explicitly in controller: `->whereHas('variables', fn($q) => $q->where('stock', '>', 0))`
- Never query ProductVariable directly without stock check

### Debug Payment Issue
1. Check webhook log: `tail -f storage/logs/laravel.log | grep -i shurjopay`
2. Verify signature in `config/shurjopay.php`
3. Test locally with Postman: POST to `/api/shurjopay/callback` with signed payload
4. Check Order model: `php artisan tinker` → `Order::latest()->first()`

### Add API Endpoint
1. Add method to `Api\FrontendController.php`
2. Add route to `routes/api.php` under v1 namespace
3. Return consistent JSON (consider adding Resource transformer)
4. Test via: `curl http://localhost:8000/api/v1/endpoint-name`

---

## Performance Optimization Tips
- Use `.select()` to limit database columns
- Eager load relationships with `.with()` to avoid N+1 queries
- Cache expensive queries with `Cache::remember()`
- Use database indexes on frequently queried columns
- Filter in model relationships (ProductVariable stock check)

---

## Modernization Roadmap (In Progress)

This codebase is transitioning from a monolith to a modern monorepo architecture:

- **Phase 1-2 (Weeks 1-6)**: Foundation & Backend - See `MIGRATION_ROADMAP.md`
- **Phase 3 (Weeks 7-10)**: Vue 3 admin & shop SPAs under `apps/admin/` and `apps/shop/`
- **Phase 4-7 (Weeks 11-20)**: Testing, migration, DevOps, launch

**Key Reference Files:**
- `START_HERE.md` - Entry point for modernization
- `MIGRATION_ROADMAP.md` - 850-line complete architecture
- `IMPLEMENTATION_GUIDE.md` - Week-by-week code examples
- `MODERNIZATION_CHECKLIST.md` - 80+ task tracking

When working on new features, consider placing code to support the modern structure where possible (e.g., organizing controllers with resource naming, using Form Requests, centralizing auth logic).
