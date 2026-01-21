import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { productsApi, categoriesApi, Product, Category } from '../api/endpoints';

export const useShopStore = defineStore('shop', () => {
  const products = ref<Product[]>([]);
  const categories = ref<Category[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const currentPage = ref(1);
  const perPage = ref(12);
  const total = ref(0);
  const selectedCategory = ref<number | null>(null);

  const isLoading = computed(() => loading.value);
  const hasError = computed(() => error.value !== null);
  const filteredProducts = computed(() => {
    if (!selectedCategory.value) return products.value;
    return products.value.filter(p => p.category_id === selectedCategory.value);
  });
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

  const fetchCategories = async () => {
    try {
      const response = await categoriesApi.list();
      if (response.success) {
        categories.value = response.data || [];
      } else {
        error.value = response.message;
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch categories';
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

  const filterByCategory = (categoryId: number | null) => {
    selectedCategory.value = categoryId;
    currentPage.value = 1;
  };

  return {
    products: filteredProducts,
    categories,
    loading: isLoading,
    error: hasError,
    currentPage,
    lastPage,
    selectedCategory,
    fetchProducts,
    fetchCategories,
    fetchFeatured,
    getProduct,
    filterByCategory,
  };
});
