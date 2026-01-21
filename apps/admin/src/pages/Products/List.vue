<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useProductStore } from '../../stores/productStore';

const router = useRouter();
const productStore = useProductStore();
const searchQuery = ref('');

onMounted(() => {
  productStore.fetchProducts();
});

const handleEdit = (id: number) => {
  router.push({ name: 'products-edit', params: { id } });
};

const handleDelete = async (id: number) => {
  if (confirm('Are you sure you want to delete this product?')) {
    const success = await productStore.deleteProduct(id);
    if (success) {
      alert('Product deleted successfully');
      productStore.fetchProducts();
    }
  }
};

const handlePageChange = (page: number) => {
  productStore.fetchProducts(page);
};
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Products</h1>
      <router-link
        :to="{ name: 'products-create' }"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
      >
        Add New Product
      </router-link>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search products..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
      />
    </div>

    <!-- Loading State -->
    <div v-if="productStore.loading" class="text-center py-8">
      <p class="text-gray-500">Loading products...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="productStore.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
      <p>{{ productStore.error }}</p>
    </div>

    <!-- Products Table -->
    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="product in productStore.products" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category?.name || 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.base_price }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="{
                  'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                  'bg-green-100 text-green-800': product.is_active,
                  'bg-gray-100 text-gray-800': !product.is_active,
                }"
              >
                {{ product.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
              <button
                @click="handleEdit(product.id)"
                class="text-blue-600 hover:text-blue-900 font-semibold"
              >
                Edit
              </button>
              <button
                @click="handleDelete(product.id)"
                class="text-red-600 hover:text-red-900 font-semibold"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            v-if="productStore.currentPage > 1"
            @click="handlePageChange(productStore.currentPage - 1)"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Previous
          </button>
          <button
            v-if="productStore.currentPage < productStore.lastPage"
            @click="handlePageChange(productStore.currentPage + 1)"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Page
              <span class="font-medium">{{ productStore.currentPage }}</span>
              of
              <span class="font-medium">{{ productStore.lastPage }}</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
