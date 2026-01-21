<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useProductStore } from '../../stores/productStore';
import { categoriesApi, brandsApi } from '../../api/endpoints';

const router = useRouter();
const productStore = useProductStore();

const form = ref({
  name: '',
  slug: '',
  description: '',
  base_price: 0,
  category_id: null,
  brand_id: null,
  is_active: true,
  is_featured: false,
});

const categories = ref([]);
const brands = ref([]);
const errors = ref<Record<string, string>>({});

onMounted(async () => {
  const [catRes, brandRes] = await Promise.all([
    categoriesApi.list(),
    brandsApi.list(),
  ]);
  categories.value = catRes.data || [];
  brands.value = brandRes.data || [];
});

const generateSlug = () => {
  form.value.slug = form.value.name
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^\w-]/g, '');
};

const handleSubmit = async () => {
  errors.value = {};
  try {
    const result = await productStore.createProduct(form.value);
    if (result) {
      alert('Product created successfully!');
      router.push({ name: 'products-list' });
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      alert('Failed to create product');
    }
  }
};
</script>

<template>
  <div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Create Product</h1>

    <form @submit.prevent="handleSubmit" class="space-y-6 bg-white p-6 rounded-lg shadow">
      <!-- Product Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
        <input
          v-model="form.name"
          type="text"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
          placeholder="Enter product name"
        />
        <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
      </div>

      <!-- Slug -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
        <div class="flex gap-2">
          <input
            v-model="form.slug"
            type="text"
            required
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
            placeholder="product-slug"
          />
          <button
            type="button"
            @click="generateSlug"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg"
          >
            Auto-generate
          </button>
        </div>
        <p v-if="errors.slug" class="text-red-500 text-sm mt-1">{{ errors.slug }}</p>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
        <textarea
          v-model="form.description"
          rows="4"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
          placeholder="Enter product description"
        ></textarea>
        <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
      </div>

      <!-- Base Price -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Base Price</label>
        <input
          v-model.number="form.base_price"
          type="number"
          step="0.01"
          min="0"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
          placeholder="0.00"
        />
        <p v-if="errors.base_price" class="text-red-500 text-sm mt-1">{{ errors.base_price }}</p>
      </div>

      <!-- Category -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
        <select
          v-model.number="form.category_id"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
        >
          <option value="">Select a category</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
        <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id }}</p>
      </div>

      <!-- Brand -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
        <select
          v-model.number="form.brand_id"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
        >
          <option value="">Select a brand</option>
          <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
        </select>
        <p v-if="errors.brand_id" class="text-red-500 text-sm mt-1">{{ errors.brand_id }}</p>
      </div>

      <!-- Status -->
      <div class="space-y-3">
        <label class="flex items-center">
          <input v-model="form.is_active" type="checkbox" class="rounded" />
          <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
        </label>
        <label class="flex items-center">
          <input v-model="form.is_featured" type="checkbox" class="rounded" />
          <span class="ml-2 text-sm font-medium text-gray-700">Featured</span>
        </label>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-4">
        <button
          type="submit"
          class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium"
        >
          Create Product
        </button>
        <button
          type="button"
          @click="() => router.back()"
          class="flex-1 bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 font-medium"
        >
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>
