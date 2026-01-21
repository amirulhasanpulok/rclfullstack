import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { productsApi, Product } from '../api/endpoints';

export const useProductStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const currentPage = ref(1);
  const perPage = ref(20);
  const total = ref(0);

  const isLoading = computed(() => loading.value);
  const hasError = computed(() => error.value !== null);
  const paginatedProducts = computed(() => products.value);
  const lastPage = computed(() => Math.ceil(total.value / perPage.value));

  const fetchProducts = async (page = 1) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsApi.list(page, perPage.value);
      if (response.success) {
        products.value = response.data || [];
        currentPage.value = response.meta?.current_page || page;
        total.value = response.meta?.total || 0;
      } else {
        error.value = response.message;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch products';
    } finally {
      loading.value = false;
    }
  };

  const fetchFeatured = async () => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsApi.featured();
      if (response.success) {
        return response.data || [];
      } else {
        error.value = response.message;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch featured products';
    } finally {
      loading.value = false;
    }
  };

  const getProduct = async (id: number | string) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsApi.get(id);
      if (response.success) {
        return response.data;
      } else {
        error.value = response.message;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch product';
    } finally {
      loading.value = false;
    }
  };

  const createProduct = async (data: Partial<Product>) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsApi.create(data);
      if (response.success) {
        products.value.unshift(response.data!);
        return response.data;
      } else {
        error.value = response.message;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to create product';
    } finally {
      loading.value = false;
    }
  };

  const updateProduct = async (id: number, data: Partial<Product>) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsApi.update(id, data);
      if (response.success) {
        const index = products.value.findIndex(p => p.id === id);
        if (index !== -1) {
          products.value[index] = response.data!;
        }
        return response.data;
      } else {
        error.value = response.message;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to update product';
    } finally {
      loading.value = false;
    }
  };

  const deleteProduct = async (id: number) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await productsApi.delete(id);
      if (response.success) {
        products.value = products.value.filter(p => p.id !== id);
        return true;
      } else {
        error.value = response.message;
        return false;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to delete product';
      return false;
    } finally {
      loading.value = false;
    }
  };

  return {
    products: paginatedProducts,
    loading: isLoading,
    error: hasError,
    currentPage,
    lastPage,
    fetchProducts,
    fetchFeatured,
    getProduct,
    createProduct,
    updateProduct,
    deleteProduct,
  };
});
