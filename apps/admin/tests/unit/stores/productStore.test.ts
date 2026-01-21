import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { createPinia, setActivePinia } from 'pinia';
import { useProductStore } from '../src/stores/productStore';
import * as endpoints from '../src/api/endpoints';

// Mock the API endpoints
vi.mock('../src/api/endpoints', () => ({
  productsApi: {
    list: vi.fn(),
    featured: vi.fn(),
    get: vi.fn(),
    create: vi.fn(),
    update: vi.fn(),
    delete: vi.fn(),
  },
  categoriesApi: {
    list: vi.fn(),
  },
  brandsApi: {
    list: vi.fn(),
  },
}));

describe('Product Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
  });

  it('initializes with empty state', () => {
    const store = useProductStore();
    expect(store.products).toEqual([]);
    expect(store.loading).toBe(false);
    expect(store.error).toBe(false);
  });

  it('fetches products successfully', async () => {
    const mockProducts = [
      { id: 1, name: 'Product 1', base_price: 99.99 },
      { id: 2, name: 'Product 2', base_price: 149.99 },
    ];

    vi.mocked(endpoints.productsApi.list).mockResolvedValueOnce({
      success: true,
      data: mockProducts,
      meta: { current_page: 1, total: 2 },
    });

    const store = useProductStore();
    await store.fetchProducts();

    expect(store.products).toEqual(mockProducts);
    expect(store.loading).toBe(false);
    expect(store.error).toBe(false);
  });

  it('handles fetch error gracefully', async () => {
    const errorMessage = 'Failed to fetch products';
    vi.mocked(endpoints.productsApi.list).mockRejectedValueOnce(
      new Error(errorMessage)
    );

    const store = useProductStore();
    await store.fetchProducts();

    expect(store.products).toEqual([]);
    expect(store.error).toBe(true);
    expect(store.loading).toBe(false);
  });

  it('creates product successfully', async () => {
    const newProduct = { name: 'New Product', base_price: 99.99 };
    const createdProduct = { id: 1, ...newProduct };

    vi.mocked(endpoints.productsApi.create).mockResolvedValueOnce({
      success: true,
      data: createdProduct,
    });

    const store = useProductStore();
    const result = await store.createProduct(newProduct);

    expect(result).toEqual(createdProduct);
    expect(store.products).toContain(createdProduct);
  });

  it('updates product successfully', async () => {
    const store = useProductStore();
    store.products = [{ id: 1, name: 'Old Name', base_price: 99.99 }];

    const updated = { id: 1, name: 'New Name', base_price: 99.99 };
    vi.mocked(endpoints.productsApi.update).mockResolvedValueOnce({
      success: true,
      data: updated,
    });

    await store.updateProduct(1, { name: 'New Name' });

    const found = store.products.find(p => p.id === 1);
    expect(found?.name).toBe('New Name');
  });

  it('deletes product successfully', async () => {
    const store = useProductStore();
    store.products = [
      { id: 1, name: 'Product 1', base_price: 99.99 },
      { id: 2, name: 'Product 2', base_price: 149.99 },
    ];

    vi.mocked(endpoints.productsApi.delete).mockResolvedValueOnce({
      success: true,
    });

    const result = await store.deleteProduct(1);

    expect(result).toBe(true);
    expect(store.products).toHaveLength(1);
    expect(store.products[0].id).toBe(2);
  });

  it('handles pagination correctly', async () => {
    const mockProducts = Array.from({ length: 20 }, (_, i) => ({
      id: i + 1,
      name: `Product ${i + 1}`,
      base_price: 99.99,
    }));

    vi.mocked(endpoints.productsApi.list).mockResolvedValueOnce({
      success: true,
      data: mockProducts,
      meta: {
        current_page: 1,
        per_page: 20,
        total: 50,
        last_page: 3,
      },
    });

    const store = useProductStore();
    await store.fetchProducts(1);

    expect(store.currentPage).toBe(1);
    expect(store.lastPage).toBe(3);
    expect(store.products).toHaveLength(20);
  });

  it('fetches featured products', async () => {
    const featured = [
      { id: 1, name: 'Featured 1', is_featured: true },
      { id: 2, name: 'Featured 2', is_featured: true },
    ];

    vi.mocked(endpoints.productsApi.featured).mockResolvedValueOnce({
      success: true,
      data: featured,
    });

    const store = useProductStore();
    const result = await store.fetchFeatured();

    expect(result).toEqual(featured);
  });
});
