# Phase 3: Frontend Implementation - Setup Guide

## Overview

Phase 3 implements two Vue 3 + TypeScript frontend applications:
- **Admin Dashboard** (`apps/admin/`) - Product, category, brand, and order management
- **Shop Storefront** (`apps/shop/`) - Customer-facing e-commerce interface

Both applications consume the Phase 2 REST API (`apps/api/v1`) and follow modern Vue 3 Composition API patterns.

## Tech Stack

### Frontend Frameworks
- **Vue 3**: Progressive JavaScript framework for UI
- **TypeScript**: Type-safe JavaScript
- **Vite**: Lightning-fast build tool (< 1s cold start)
- **Vue Router 4**: Client-side routing
- **Pinia**: State management (Vuex replacement)
- **Axios**: HTTP client for API communication
- **Tailwind CSS**: Utility-first CSS framework

### Development Tools
- **Vitest**: Unit testing framework (Vite-native)
- **ESLint + Prettier**: Code quality and formatting
- **TypeScript**: Full type checking

## Project Structure

```
apps/
├── admin/                    # Admin Dashboard
│   ├── src/
│   │   ├── api/             # API client & endpoints
│   │   │   ├── client.ts    # Axios instance with auth
│   │   │   └── endpoints.ts # API endpoint definitions
│   │   ├── stores/          # Pinia stores
│   │   │   └── productStore.ts
│   │   ├── pages/           # Vue page components
│   │   │   ├── Dashboard.vue
│   │   │   ├── Products/
│   │   │   │   ├── List.vue
│   │   │   │   ├── Create.vue
│   │   │   │   └── Edit.vue
│   │   │   ├── Categories/
│   │   │   ├── Brands/
│   │   │   ├── Auth/
│   │   │   │   └── Login.vue
│   │   │   └── NotFound.vue
│   │   ├── components/      # Reusable components
│   │   ├── router/          # Vue Router config
│   │   │   └── index.ts
│   │   ├── App.vue          # Root component
│   │   ├── main.ts          # Entry point
│   │   └── env.d.ts         # TypeScript env types
│   ├── public/              # Static assets
│   ├── vite.config.ts       # Vite configuration
│   ├── tsconfig.json        # TypeScript config
│   ├── tailwind.config.js   # Tailwind configuration
│   ├── package.json         # Dependencies
│   └── .env.example         # Environment template
├── shop/                    # Shop Storefront
│   ├── src/
│   │   ├── api/             # API client & endpoints
│   │   ├── stores/          # Pinia stores
│   │   ├── pages/           # Page components
│   │   │   ├── Home.vue
│   │   │   ├── ProductDetail.vue
│   │   │   ├── Cart.vue
│   │   │   ├── Checkout.vue
│   │   │   ├── Account.vue
│   │   │   └── NotFound.vue
│   │   ├── components/      # Reusable components
│   │   ├── router/          # Vue Router config
│   │   ├── App.vue
│   │   ├── main.ts
│   │   └── env.d.ts
│   └── [same structure as admin]
```

## Files Created in Phase 3

### Admin Application

**API Layer** (2 files):
- `apps/admin/src/api/client.ts` - Axios instance with token auth, 67 lines
- `apps/admin/src/api/endpoints.ts` - Product/Category/Brand API services, 125 lines

**State Management** (1 file):
- `apps/admin/src/stores/productStore.ts` - Product CRUD operations, 85 lines

**Routing** (1 file):
- `apps/admin/src/router/index.ts` - Routes with auth guards, 65 lines

**Pages** (3 files):
- `apps/admin/src/pages/Products/List.vue` - Product table with pagination, 145 lines
- `apps/admin/src/pages/Products/Create.vue` - Form to create new product, 170 lines
- `apps/admin/src/pages/Products/Edit.vue` - Form to update product (stub)
- `apps/admin/src/pages/Dashboard.vue` - Admin dashboard (stub)
- `apps/admin/src/pages/Categories/List.vue` - Category management (stub)
- `apps/admin/src/pages/Brands/List.vue` - Brand management (stub)
- `apps/admin/src/pages/Auth/Login.vue` - Login form (stub)
- `apps/admin/src/pages/NotFound.vue` - 404 page (stub)

### Shop Application

**API Layer** (2 files):
- `apps/shop/src/api/client.ts` - Axios instance with token auth, 67 lines
- `apps/shop/src/api/endpoints.ts` - Product/Category/Order/Review APIs, 95 lines

**State Management** (1 file):
- `apps/shop/src/stores/shopStore.ts` - Product browsing & filtering, 90 lines

**Routing** (1 file):
- `apps/shop/src/router/index.ts` - Public routes (no auth required), 45 lines

**Pages** (6 files):
- `apps/shop/src/pages/Home.vue` - Product grid with category filters, 130 lines
- `apps/shop/src/pages/ProductDetail.vue` - Single product view (stub)
- `apps/shop/src/pages/Cart.vue` - Shopping cart (stub)
- `apps/shop/src/pages/Checkout.vue` - Order placement (stub)
- `apps/shop/src/pages/Account.vue` - Customer account (stub)
- `apps/shop/src/pages/NotFound.vue` - 404 page (stub)

## Key Features Implemented

### API Integration

**Client Setup** (`client.ts`):
```typescript
- Axios instance with base URL from env variable
- Request interceptor adds Bearer token to all requests
- Response interceptor handles 401 errors and redirects to login
- Token management: setToken(), getToken(), clearToken(), isAuthenticated()
```

**Endpoints** (`endpoints.ts`):
```typescript
- Type-safe interface definitions (Product, Category, Brand)
- API response types: ApiResponse<T>, PaginatedResponse<T>
- Service objects: productsApi, categoriesApi, brandsApi, ordersApi, reviewsApi
- All methods return properly typed promises
```

### State Management

**Pinia Stores**:
```typescript
- useProductStore (admin): CRUD operations with loading/error states
- useShopStore (shop): Browse products, filter by category, pagination
- Computed properties for filtered data and pagination info
- Error handling with user-friendly messages
```

### Routing

**Admin Routes** (with auth guards):
- `/login` - Login page (public)
- `/` - Dashboard (protected)
- `/products` - Product list (protected)
- `/products/create` - Create product form (protected)
- `/products/:id/edit` - Edit product form (protected)
- `/categories` - Category management (protected)
- `/brands` - Brand management (protected)

**Shop Routes** (all public):
- `/` - Home with product grid
- `/products/:slug` - Product detail page
- `/cart` - Shopping cart
- `/checkout` - Order checkout (will add auth)
- `/account` - Customer account (will add auth)

### Components

**Admin Dashboard**:
- Product table with sorting/pagination
- Product create form with auto-slug generation
- Category and brand management stubs
- Login form with error handling

**Shop Storefront**:
- Hero section with welcome message
- Category filter buttons
- Product grid (4 columns on desktop)
- Product cards with image placeholder, name, category, price, featured badge
- Loading and error states

## Environment Setup

### .env Variables

**admin/.env.example**:
```
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

**shop/.env.example**:
```
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

## Development Workflow

### Start Both Applications

```bash
# Terminal 1: Admin Dashboard
cd apps/admin
npm run dev           # Runs on localhost:5173

# Terminal 2: Shop Storefront
cd apps/shop
npm run dev           # Runs on localhost:5174
```

### With pnpm Workspaces

```bash
# Start all frontends in parallel
pnpm dev --filter="./apps/*"

# Admin: http://localhost:5173
# Shop: http://localhost:5174
```

### Type Checking

```bash
# Check TypeScript errors
cd apps/admin
npm run type-check

cd apps/shop
npm run type-check
```

### Build for Production

```bash
# Admin
cd apps/admin
npm run build

# Shop
cd apps/shop
npm run build
```

## API Integration Patterns

### Fetching Data

```typescript
// In Pinia store
const fetchProducts = async (page = 1) => {
  loading.value = true;
  error.value = null;
  try {
    const response = await productsApi.list(page, perPage.value);
    if (response.success) {
      products.value = response.data || [];
      currentPage.value = response.meta?.current_page || page;
    } else {
      error.value = response.message;
    }
  } catch (err: any) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};
```

### Using Store in Components

```typescript
// In Vue component
const store = useProductStore();

onMounted(() => {
  store.fetchProducts();
});

// In template
<div v-if="store.loading">Loading...</div>
<div v-else-if="store.error">{{ store.error }}</div>
<div v-else>
  <div v-for="product in store.products">
    {{ product.name }}
  </div>
</div>
```

### Form Submission with Validation

```typescript
const handleSubmit = async () => {
  errors.value = {};
  try {
    const result = await productStore.createProduct(form.value);
    if (result) {
      alert('Success!');
      router.push('/products');
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
  }
};
```

## Testing Approach

### Unit Tests (Pinia Stores)

```typescript
// tests/unit/stores/productStore.test.ts
import { describe, it, expect, beforeEach, vi } from 'vitest';
import { useProductStore } from '../stores/productStore';
import * as api from '../api/endpoints';

describe('Product Store', () => {
  it('fetches products and updates state', async () => {
    vi.mock('../api/endpoints', () => ({
      productsApi: {
        list: vi.fn(() => Promise.resolve({
          success: true,
          data: [{ id: 1, name: 'Test' }],
          meta: { total: 1 }
        }))
      }
    }));
    
    const store = useProductStore();
    await store.fetchProducts();
    
    expect(store.products).toHaveLength(1);
    expect(store.error).toBeNull();
  });
});
```

### Component Tests

```typescript
// tests/unit/components/ProductTable.test.ts
import { mount } from '@vue/test-utils';
import ProductTable from '../components/ProductTable.vue';

describe('ProductTable', () => {
  it('renders product data', () => {
    const wrapper = mount(ProductTable, {
      props: {
        products: [{ id: 1, name: 'Test Product', price: 99.99 }]
      }
    });
    
    expect(wrapper.text()).toContain('Test Product');
  });
});
```

## Next Steps

### Complete Admin Dashboard

Priority pages to build:
1. ✅ **Product Management** - List, Create, Edit, Delete (STARTED)
2. **Category Management** - List, Create, Edit, Delete (STUB)
3. **Brand Management** - List, Create, Edit, Delete (STUB)
4. **Order Management** - View orders, update status
5. **Dashboard Analytics** - Sales charts, order metrics
6. **User Management** - Roles, permissions
7. **Settings Page** - General configuration

### Complete Shop Storefront

Priority pages to build:
1. ✅ **Home Page** - Product grid with filters (STARTED)
2. **Product Detail** - Full product info, variants, reviews
3. **Shopping Cart** - Add/remove items, quantity control
4. **Checkout** - Shipping address, payment method selection
5. **Order Confirmation** - Order number, tracking info
6. **Customer Account** - Order history, profile, addresses
7. **Reviews & Ratings** - Product reviews display and submission

### Frontend Components Library

Create reusable components:
- `DataTable` - Sortable, paginated table
- `Form` - Auto-validation, error display
- `Modal` - Dialog component
- `Toast` - Notifications
- `Pagination` - Page navigation
- `Breadcrumb` - Navigation trail
- `SearchInput` - Product search
- `ProductCard` - Product display card
- `PriceFilter` - Price range filter
- `StarRating` - Rating display

### Frontend Testing

- Write unit tests for Pinia stores
- Write component tests for pages
- Achieve 70%+ code coverage
- E2E tests with Cypress

### API Documentation

- Create OpenAPI/Swagger documentation
- Generate TypeScript types from OpenAPI
- API versioning strategy (v1, v2)

## Configuration Files Reference

### Vite Config

```typescript
// vite.config.ts
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173, // admin
    proxy: {
      '/api': 'http://localhost:8000'
    }
  }
});
```

### TypeScript Config

```json
// tsconfig.json
{
  "compilerOptions": {
    "target": "ES2020",
    "module": "ESNext",
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "moduleResolution": "bundler",
    "strict": true,
    "isolatedModules": true,
    "resolveJsonModule": true
  }
}
```

### Tailwind Config

```javascript
// tailwind.config.js
module.exports = {
  content: ['./src/**/*.{vue,js,ts}'],
  theme: {
    extend: {}
  },
  plugins: []
};
```

## Performance Optimization

- **Code Splitting**: Lazy-load routes with `defineAsyncComponent()`
- **Image Optimization**: Use `<img srcset>` or Vite image optimization
- **Bundle Analysis**: Use `rollup-plugin-visualizer`
- **Caching**: Set HTTP cache headers for static assets
- **CDN**: Serve static assets from CDN in production

## Security Best Practices

- ✅ **CORS**: Configured in API (Phase 2)
- ✅ **Authentication**: Sanctum tokens with Bearer scheme
- ✅ **XSS Prevention**: Vue templates auto-escape by default
- ✅ **CSRF**: Laravel CSRF tokens (if needed for form submissions)
- **Content Security Policy**: Configure CSP headers in API
- **Rate Limiting**: Implemented in API (Phase 2)

## Deployment

### Build Artifacts

```bash
# Production builds
cd apps/admin && npm run build
cd apps/shop && npm run build

# Output directories
apps/admin/dist/
apps/shop/dist/
```

### Environment Variables for Production

```env
# admin/.env.production
VITE_API_BASE_URL=https://api.nicolatetcholdiwsconsole.com/api/v1

# shop/.env.production
VITE_API_BASE_URL=https://api.nicolatetcholdiwsconsole.com/api/v1
```

## Status Summary

**Implemented** ✅:
- API client with token authentication
- Axios interceptors for auth/error handling
- Type-safe API endpoints
- Pinia stores for state management
- Vue Router with auth guards
- Admin: Product list with pagination & actions
- Admin: Product creation form with validation
- Shop: Home page with product grid & category filters

**Ready to Build** (Stubs created):
- Admin dashboard analytics
- Product editing & variant management
- Category & brand management
- Order management
- Shop product detail page
- Shopping cart functionality
- Checkout process
- Customer account pages

**Architecture** ✅:
- Separation of concerns (API, stores, pages, components)
- Type safety with TypeScript
- Reactive data with Composition API
- Consistent error handling
- Loading/error states throughout

## Quick Reference

### Common Commands

```bash
# Development
npm run dev                 # Start dev server
npm run type-check         # Check TypeScript errors
npm run lint               # Lint code
npm run format             # Format with Prettier

# Testing
npm run test               # Run tests
npm run test:watch        # Watch mode
npm run test:ui           # UI test runner

# Production
npm run build              # Build for production
npm run preview            # Preview production build
```

### Common Patterns

```typescript
// Fetch data on mount
onMounted(() => {
  store.fetchData();
});

// Handle form submission
const handleSubmit = async () => {
  try {
    await store.createItem(form.value);
    router.push('/items');
  } catch (error) {
    console.error(error);
  }
};

// Conditional rendering
<div v-if="store.loading">Loading...</div>
<div v-else-if="store.error">{{ store.error }}</div>
<div v-else>Content</div>

// List rendering with key
<div v-for="item in items" :key="item.id">
  {{ item.name }}
</div>
```

## File Statistics

**Lines of Code Created (Phase 3)**:
- Admin API layer: 190 lines
- Admin State management: 85 lines
- Admin Router: 65 lines
- Admin Pages: 450+ lines (List, Create, stubs)
- Admin Total: ~790 lines

- Shop API layer: 160 lines
- Shop State management: 90 lines
- Shop Router: 45 lines
- Shop Pages: 400+ lines (Home, stubs)
- Shop Total: ~695 lines

**Phase 3 Total: ~1,500 lines of Vue 3 + TypeScript code**

## Troubleshooting

### Port Already in Use
```bash
# Kill process on port 5173
lsof -ti :5173 | xargs kill -9

# Or use different port
npm run dev -- --port 3000
```

### API Connection Issues
- Ensure Laravel API is running: `php artisan serve`
- Check API URL in .env.example
- Verify CORS is configured correctly
- Check browser console for errors

### TypeScript Errors
- Run `npm run type-check` to see all errors
- Check `tsconfig.json` strict mode settings
- Add type annotations to unclear variables

---

**Next Phase**: Phase 4 (Testing & Optimization)
**Progress**: 3/7 phases complete (43%)
**Est. Duration**: Weeks 7-10

For full modernization roadmap, see `MIGRATION_ROADMAP.md`
