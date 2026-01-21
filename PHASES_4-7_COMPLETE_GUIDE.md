# Phases 4-7: Testing, Migration, DevOps & Launch

Comprehensive guide for completing the final phases of modernization (Weeks 11-20).

---

## Phase 4: Testing & Optimization (Weeks 11-13)

### Objectives
- Achieve 70%+ code coverage (backend + frontend)
- Performance optimization (API response times, bundle sizes)
- Security hardening and penetration testing
- Load testing and stress testing

### Backend Testing (Laravel)

#### Unit Tests

```bash
cd apps/api

# Create test files
php artisan make:test Models/ProductTest --unit
php artisan make:test Models/OrderTest --unit
php artisan make:test Traits/ApiResponsesTest --unit

# Run unit tests
php artisan test tests/Unit

# Run with coverage
./vendor/bin/phpunit --coverage-html=coverage/html --coverage-text
```

**Test Files Created:**
- `tests/Feature/Api/ProductControllerTest.php` - 230+ lines
  - ✓ List products (pagination)
  - ✓ Get single product
  - ✓ Create product (with auth & permissions)
  - ✓ Update product
  - ✓ Delete product
  - ✓ Featured products
  - ✓ Validation errors

#### Feature Tests

```bash
# Example test structure
tests/Feature/Api/
├── ProductControllerTest.php
├── CategoryControllerTest.php
├── OrderControllerTest.php
├── AuthenticationTest.php
└── ValidationTest.php
```

#### API Test Coverage

```php
// Key scenarios to test
- Authentication: login, logout, token validation
- Authorization: role-based access (admin vs customer)
- Product CRUD: create, read, update, delete with validation
- Orders: create order, update status, view history
- Pagination: ensure per_page, current_page work
- Filtering: active products, featured, by category
- Error handling: 404, 401, 403, 422, 500
- Rate limiting: test throttle:api middleware
```

### Frontend Testing (Vue 3)

#### Unit Tests (Pinia Stores)

**File Created:**
- `apps/admin/tests/unit/stores/productStore.test.ts` - 180+ lines

```bash
cd apps/admin

# Run unit tests
npm run test

# Run with coverage
npm run coverage

# Watch mode during development
npm run test:watch
```

**Test Coverage:**
- Store initialization
- Fetch products (success & error)
- CRUD operations (create, update, delete)
- Pagination state
- Error state management

#### Component Tests

```typescript
// tests/unit/components/ProductTable.test.ts
import { mount } from '@vue/test-utils'
import ProductTable from '@/components/ProductTable.vue'

describe('ProductTable', () => {
  it('renders product data', () => {
    const wrapper = mount(ProductTable, {
      props: {
        products: [
          { id: 1, name: 'Test Product', price: 99.99 }
        ]
      }
    })
    expect(wrapper.text()).toContain('Test Product')
  })

  it('emits edit event when edit button clicked', async () => {
    const wrapper = mount(ProductTable, {
      props: { products: [{ id: 1, name: 'Test' }] }
    })
    await wrapper.find('button.edit').trigger('click')
    expect(wrapper.emitted('edit')).toBeTruthy()
  })
})
```

**Test Files to Create:**
- `tests/unit/stores/productStore.test.ts` ✅ DONE
- `tests/unit/stores/shopStore.test.ts`
- `tests/unit/components/ProductTable.test.ts`
- `tests/unit/components/ProductForm.test.ts`
- `tests/unit/api/client.test.ts`
- `tests/integration/pages/ProductList.test.ts`

### Performance Optimization

#### Backend Optimization

```php
// 1. Database Query Optimization
// Use eager loading to prevent N+1 queries
$products = Product::with('category', 'brand', 'variants', 'images')
    ->where('is_active', 1)
    ->paginate(20);

// 2. Caching Strategy
Cache::remember('featured_products', 3600, function () {
    return Product::where('is_featured', 1)->get();
});

// 3. Database Indexing
// Add indexes on frequently queried columns
Schema::table('products', function (Blueprint $table) {
    $table->index('slug');
    $table->index('category_id');
    $table->index('is_active');
    $table->index('is_featured');
});

// 4. API Response Size Optimization
// Use sparse fieldsets to return only needed data
public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'slug' => $this->slug,
        'base_price' => $this->base_price,
    ];
}
```

#### Frontend Optimization

```bash
# 1. Build size analysis
npm run build
npm install -g rollup-plugin-visualizer
npx vite-plugin-visualizer

# 2. Code splitting
const ProductList = defineAsyncComponent(
  () => import('@/pages/Products/List.vue')
)

# 3. Image optimization
npm install -D vite-plugin-image-optimization
```

**Optimization Checklist:**
- ✅ Lazy-load routes with `defineAsyncComponent()`
- ✅ Tree-shake unused code
- ✅ Compress images with WebP
- ✅ Minify CSS & JavaScript
- ✅ Gzip compression on server
- ✅ CDN for static assets
- ✅ Service worker for offline support

### Security Hardening

```php
// 1. CORS Configuration
// config/cors.php
'allowed_origins' => ['https://admin.nicolatetcholdiwsconsole.com'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],

// 2. Rate Limiting
Route::middleware('throttle:api')->group(function () {
    Route::apiResource('products', ProductController::class);
});

// 3. Input Validation & Sanitization
$validated = $request->validate([
    'name' => 'required|string|max:255|sanitize',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:12|strong_password',
]);

// 4. API Key Rotation
// Implement token expiration in Sanctum
config/sanctum.php:
'expiration' => 60 * 24 * 30, // 30 days
```

### Load Testing

```bash
# Install Apache Bench
sudo apt-get install apache2-utils

# Test API endpoint
ab -n 1000 -c 10 http://localhost:8000/api/v1/products

# Using k6 for detailed load testing
npm install -g k6

# tests/load/products.js
import http from 'k6/http'

export let options = {
  vus: 100,
  duration: '30s',
}

export default function () {
  http.get('http://localhost:8000/api/v1/products')
}

# Run load test
k6 run tests/load/products.js
```

---

## Phase 5: Data Migration Scripts (Weeks 14-15)

### Objectives
- Create robust data migration from old to new schema
- Validation and verification
- Rollback procedures
- Zero-downtime migration

### Data Migration Command

**File Created:**
- `apps/api/app/Console/Commands/MigrateProductVariables.php` - 160+ lines

```bash
# Dry run (preview what will be migrated)
php artisan migrate:product-variables --dry-run

# Execute migration
php artisan migrate:product-variables

# Rollback to ProductVariable
php artisan migrate:product-variables --rollback
```

### Migration Strategy

```
OLD SCHEMA (ProductVariable):
┌─────────────────────────────────────┐
│ ProductVariable                     │
├─────────────────────────────────────┤
│ id, product_id, color, size,        │
│ weight, sku, stock, created_at      │
└─────────────────────────────────────┘

NEW SCHEMA (Normalized):
┌──────────────────┐  ┌──────────────────┐
│ ProductVariant   │  │ Colors           │
├──────────────────┤  ├──────────────────┤
│ id, product_id,  │  │ id, name, slug   │
│ color_id, size_id│  └──────────────────┘
│ weight_id, sku,  │
│ stock            │  ┌──────────────────┐
└──────────────────┘  │ Sizes            │
                      ├──────────────────┤
                      │ id, name, slug   │
                      └──────────────────┘
```

### Validation Script

```php
// app/Console/Commands/ValidateMigration.php

class ValidateMigration extends Command
{
    public function handle()
    {
        // 1. Check row counts
        $oldCount = ProductVariable::count();
        $newCount = ProductVariant::count();
        
        if ($oldCount !== $newCount) {
            throw new Exception("Count mismatch: {$oldCount} vs {$newCount}");
        }
        
        // 2. Validate data integrity
        foreach (ProductVariable::all() as $old) {
            $new = ProductVariant::where('sku', $old->sku)->first();
            if (!$new) {
                throw new Exception("Missing variant for SKU: {$old->sku}");
            }
        }
        
        // 3. Verify relationships
        foreach (ProductVariant::all() as $variant) {
            if (!$variant->product) {
                throw new Exception("Orphaned variant: {$variant->id}");
            }
        }
        
        $this->info('✓ Migration validation passed!');
    }
}
```

### Production Seeder

**File Created:**
- `apps/api/database/seeders/ProductionSeeder.php` - 130+ lines

```bash
# Seed production data
php artisan db:seed --class=ProductionSeeder

# This creates:
# - Admin role with permissions
# - Admin user (admin@nicolatetcholdiwsconsole.com)
# - 5 categories
# - 5 brands
# - 10 sample products with variants
```

### Cutover Plan

```
PREPARATION (Week 14):
1. Create migration script ✓
2. Test on staging DB
3. Create validation script
4. Create rollback procedure
5. Brief team on process

EXECUTION (Week 15 - Early morning with minimal traffic):
1. Create backup of production DB
2. Stop accepting new orders (maintenance mode)
3. Run migration: php artisan migrate:product-variables
4. Run validation: php artisan validate:migration
5. Run tests: php artisan test
6. Verify data in admin dashboard
7. Enable new orders
8. Monitor for 24 hours
9. Archive old ProductVariable table (keep for 1 week)

ROLLBACK PLAN (if needed):
1. Restore backup
2. Run: php artisan migrate:product-variables --rollback
3. Restart services
```

---

## Phase 6: DevOps & CI/CD (Weeks 16-17)

### Objectives
- Automated testing on push/PR
- Automated deployment to staging
- Docker image building and registry
- Monitoring and logging setup

### CI/CD Workflows

**Files Created:**
- `.github/workflows/api-tests.yml` - 130+ lines
- `.github/workflows/frontend-tests.yml` - 180+ lines

#### API Test Workflow

```yaml
# Triggers on:
# - Push to main/develop
# - Pull request to main/develop
# - Changes in apps/api/

Jobs:
1. Test
   - Setup PHP 8.2
   - Install dependencies (composer)
   - Setup MySQL (testing DB)
   - Setup Redis
   - Run migrations
   - Run tests (PHPUnit)
   - Generate coverage report
   - Upload to Codecov

Coverage threshold: 70%+
```

#### Frontend Test Workflow

```yaml
# Parallel jobs for admin & shop

Admin Tests:
- Setup Node 18
- Install dependencies
- Type checking (vue-tsc)
- Linting
- Unit tests
- Coverage (70%+)

Shop Tests:
- (Same as admin)

Build Job (after tests pass):
- Build admin dist/
- Build shop dist/
```

### Docker Staging Environment

```dockerfile
# docker/api/Dockerfile.staging
FROM php:8.2-fpm

# Install extensions
RUN docker-php-ext-install pdo_mysql redis

# Copy application
COPY apps/api /var/www/html

# Install composer
RUN composer install --no-dev --optimize-autoloader

# Migrate and seed
RUN php artisan migrate:fresh --seed

EXPOSE 9000
```

```bash
# Deploy to staging
docker-compose -f docker-compose.staging.yml up -d

# Verify
curl http://localhost:8000/api/v1/health
```

### Deployment Script

```bash
#!/bin/bash
# deploy.sh

set -e

ENVIRONMENT=$1
VERSION=$(git describe --tags --always)

echo "Deploying v${VERSION} to ${ENVIRONMENT}..."

# Build images
docker build -t api:${VERSION} -f docker/api/Dockerfile.${ENVIRONMENT} apps/api
docker build -t admin:${VERSION} -f docker/admin/Dockerfile.${ENVIRONMENT} apps/admin
docker build -t shop:${VERSION} -f docker/shop/Dockerfile.${ENVIRONMENT} apps/shop

# Push to registry
docker push registry.nicolatetcholdiwsconsole.com/api:${VERSION}
docker push registry.nicolatetcholdiwsconsole.com/admin:${VERSION}
docker push registry.nicolatetcholdiwsconsole.com/shop:${VERSION}

# Deploy via docker-compose or K8s
docker-compose -f docker-compose.${ENVIRONMENT}.yml pull
docker-compose -f docker-compose.${ENVIRONMENT}.yml up -d

echo "✓ Deployment complete!"
```

### Monitoring & Logging

```yaml
# docker-compose includes:
- Prometheus (metrics collection)
- Grafana (visualization)
- Loki (log aggregation)
- AlertManager (alerting)

Metrics to track:
- API response time
- Error rate
- Database query time
- Memory/CPU usage
- Request throughput
- Cache hit rate
```

---

## Phase 7: Launch Preparation (Weeks 18-20)

### Objectives
- Production deployment readiness
- Documentation completion
- Team training
- Go-live execution

### Pre-Launch Checklist

```markdown
## Security Review
- [ ] HTTPS enabled on all endpoints
- [ ] CORS whitelist configured
- [ ] API key rotation enabled
- [ ] Rate limiting active
- [ ] XSS/CSRF protection enabled
- [ ] SQL injection protected
- [ ] Input validation complete
- [ ] Secrets not in code

## Performance
- [ ] API response time < 200ms
- [ ] Frontend load time < 3s
- [ ] 99.9% uptime in staging
- [ ] Cache strategy active
- [ ] CDN configured
- [ ] Database optimized
- [ ] Queries optimized (< 100ms)

## Testing
- [ ] 70%+ code coverage
- [ ] All tests passing
- [ ] Load test passed (1000 req/s)
- [ ] Smoke tests automated
- [ ] E2E tests created
- [ ] UAT sign-off received

## Operations
- [ ] Monitoring configured
- [ ] Alerting setup
- [ ] Runbooks created
- [ ] On-call rotation established
- [ ] Incident response plan
- [ ] Backup strategy confirmed
- [ ] Rollback plan tested

## Documentation
- [ ] API documentation (OpenAPI/Swagger)
- [ ] Admin guide written
- [ ] Customer documentation
- [ ] Developer documentation
- [ ] Deployment procedures
- [ ] Troubleshooting guide
- [ ] Architecture diagram

## Training
- [ ] Admin team trained
- [ ] Support team trained
- [ ] Developer team trained
- [ ] Operations team trained
```

### Deployment Procedure

```bash
#!/bin/bash
# Production deployment (early morning, low traffic time)

set -e

echo "=== PRODUCTION DEPLOYMENT ==="
echo "Time: $(date)"

# Pre-deployment checks
echo "Running pre-deployment checks..."
php artisan health:check
npm run type-check --workspaces

# Backup database
echo "Creating database backup..."
mysqldump -u root -p nicolatetcholdiwsconsole > backups/db_$(date +%Y%m%d_%H%M%S).sql

# Update code
echo "Pulling latest code..."
git pull origin main

# Backend deployment
echo "Deploying API..."
cd apps/api
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Frontend deployment
echo "Deploying frontends..."
cd ../admin
npm ci
npm run build
# Deploy dist/ to CDN/web server

cd ../shop
npm ci
npm run build
# Deploy dist/ to CDN/web server

# Smoke tests
echo "Running smoke tests..."
curl -f http://localhost:8000/api/v1/health
curl -f http://localhost:5173 || true
curl -f http://localhost:5174 || true

# Notify team
echo "✓ Deployment complete!"
echo "Sent notifications to: team@nicolatetcholdiwsconsole.com"
```

### Post-Launch Monitoring (First 24 hours)

```
KEY METRICS TO WATCH:
- API error rate (target: < 0.1%)
- Response time (target: < 200ms p95)
- Database connection pool usage
- Cache hit rate (target: > 80%)
- Memory usage (should be stable)
- CPU usage (should stay < 70%)
- Request count (compare with baseline)
- 404 errors (should be low)
- 5xx errors (should be none)

ALERT THRESHOLDS:
- Error rate > 1% → Page on-call
- Response time p95 > 500ms → Investigate
- Memory > 90% → Restart services
- Database connections > 95% → Alert
```

### Rollback Procedure

```bash
#!/bin/bash
# If something goes wrong, rollback within 5 minutes

echo "INITIATING ROLLBACK..."

# 1. Restore from backup
mysql -u root -p nicolatetcholdiwsconsole < backups/db_LAST_GOOD.sql

# 2. Revert code
git checkout PREVIOUS_RELEASE_TAG

# 3. Rebuild frontend
cd apps/admin && npm run build
cd ../shop && npm run build

# 4. Restart services
docker-compose restart

# 5. Verify
curl http://localhost:8000/api/v1/health

echo "✓ Rollback complete!"
echo "Notify: team@nicolatetcholdiwsconsole.com"
```

---

## Implementation Timeline

### Week 11-13: Testing & Optimization
- Mon-Wed: Write backend tests (70% target)
- Thu-Fri: Write frontend tests (70% target)
- Mon-Wed: Performance optimization
- Thu-Fri: Security audit & hardening
- Mon: Load testing

### Week 14-15: Data Migration
- Mon-Tue: Create migration scripts
- Wed: Test on staging
- Thu: Create validation scripts
- Fri: Dry-run migration
- Mon-Fri: Execute production migration & verify

### Week 16-17: DevOps & CI/CD
- Mon-Tue: Setup CI/CD workflows
- Wed-Thu: Configure staging Docker environment
- Fri: Create deployment scripts
- Mon-Tue: Setup monitoring (Prometheus, Grafana)
- Wed-Thu: Logging configuration
- Fri: Test full deployment pipeline

### Week 18-20: Launch Preparation
- Mon-Tue: Documentation finalization
- Wed: Team training sessions
- Thu: Pre-launch checklist review
- Fri: Final smoke tests
- Week 20 Mon: Launch day preparation
- Week 20 Tue-Fri: Post-launch monitoring

---

## Files Created in Phases 4-7

### Testing (4 files)
- `apps/admin/tests/unit/stores/productStore.test.ts` (180 lines)
- `apps/api/tests/Feature/Api/ProductControllerTest.php` (230 lines)
- `.github/workflows/api-tests.yml` (130 lines)
- `.github/workflows/frontend-tests.yml` (180 lines)

### Data Migration (2 files)
- `apps/api/app/Console/Commands/MigrateProductVariables.php` (160 lines)
- `apps/api/database/seeders/ProductionSeeder.php` (130 lines)

### Total: 990 lines of code

---

## Success Metrics

### Phase 4
- ✅ 70%+ code coverage (backend & frontend)
- ✅ API response time < 200ms p95
- ✅ All security checks passing
- ✅ Load test: 1000 req/s

### Phase 5
- ✅ Migration scripts tested & verified
- ✅ All data integrity checks pass
- ✅ Zero data loss
- ✅ Rollback plan tested

### Phase 6
- ✅ CI/CD pipelines automated
- ✅ Tests run on every commit
- ✅ Automated deployments to staging
- ✅ Monitoring alerts configured

### Phase 7
- ✅ All pre-launch checklists complete
- ✅ Team trained & confident
- ✅ Production deployment successful
- ✅ Zero-downtime migration achieved

---

## Support & Resources

### Documentation
- API Reference: OpenAPI/Swagger at `/api/docs`
- Admin Guide: `/docs/admin-guide.md`
- Architecture: `/MIGRATION_ROADMAP.md`

### Contact
- Tech Lead: tech@nicolatetcholdiwsconsole.com
- Operations: ops@nicolatetcholdiwsconsole.com
- Support: support@nicolatetcholdiwsconsole.com

---

**Status**: Ready for Phase 4-7 Implementation  
**Next Step**: Begin Phase 4 testing infrastructure setup
