<script setup lang="ts">
import { onMounted } from 'vue';
import { useShopStore } from '../stores/shopStore';

const shopStore = useShopStore();

onMounted(() => {
  shopStore.fetchProducts();
  shopStore.fetchCategories();
});
</script>

<template>
  <div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Welcome to Nicola Shop</h1>
        <p class="text-xl text-blue-100">Discover our amazing collection of products</p>
      </div>
    </div>

    <!-- Categories -->
    <div class="container mx-auto px-4 py-8">
      <h2 class="text-2xl font-bold mb-6">Categories</h2>
      <div class="flex gap-4 overflow-x-auto pb-2">
        <button
          @click="shopStore.filterByCategory(null)"
          :class="{
            'px-6 py-2 rounded-lg whitespace-nowrap font-medium': true,
            'bg-blue-600 text-white': shopStore.selectedCategory === null,
            'bg-gray-200 text-gray-800': shopStore.selectedCategory !== null,
          }"
        >
          All Products
        </button>
        <button
          v-for="category in shopStore.categories"
          :key="category.id"
          @click="shopStore.filterByCategory(category.id)"
          :class="{
            'px-6 py-2 rounded-lg whitespace-nowrap font-medium': true,
            'bg-blue-600 text-white': shopStore.selectedCategory === category.id,
            'bg-gray-200 text-gray-800': shopStore.selectedCategory !== category.id,
          }"
        >
          {{ category.name }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="shopStore.loading" class="container mx-auto px-4 py-8 text-center">
      <p class="text-gray-500 text-lg">Loading products...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="shopStore.error" class="container mx-auto px-4 py-8">
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
        <p>{{ shopStore.error }}</p>
      </div>
    </div>

    <!-- Products Grid -->
    <div v-else class="container mx-auto px-4 py-8">
      <h2 class="text-2xl font-bold mb-6">Products</h2>
      <div v-if="shopStore.products.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg">No products found</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <router-link
          v-for="product in shopStore.products"
          :key="product.id"
          :to="{ name: 'product-detail', params: { slug: product.slug } }"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
        >
          <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
            <span class="text-gray-400">Product Image</span>
          </div>
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ product.name }}</h3>
            <p class="text-sm text-gray-600 truncate">{{ product.category?.name }}</p>
            <div class="flex justify-between items-center mt-4">
              <span class="text-xl font-bold text-blue-600">{{ product.base_price }}</span>
              <span
                v-if="product.is_featured"
                class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded"
              >
                Featured
              </span>
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>
