import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import { apiClient } from '../api/client';
import Dashboard from '../pages/Dashboard.vue';
import ProductsList from '../pages/Products/List.vue';
import ProductsCreate from '../pages/Products/Create.vue';
import ProductsEdit from '../pages/Products/Edit.vue';
import CategoriesList from '../pages/Categories/List.vue';
import BrandsList from '../pages/Brands/List.vue';
import Login from '../pages/Auth/Login.vue';
import NotFound from '../pages/NotFound.vue';

const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false, layout: 'blank' },
  },
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true, title: 'Dashboard' },
  },
  {
    path: '/products',
    name: 'products-list',
    component: ProductsList,
    meta: { requiresAuth: true, title: 'Products' },
  },
  {
    path: '/products/create',
    name: 'products-create',
    component: ProductsCreate,
    meta: { requiresAuth: true, title: 'Create Product' },
  },
  {
    path: '/products/:id/edit',
    name: 'products-edit',
    component: ProductsEdit,
    meta: { requiresAuth: true, title: 'Edit Product' },
  },
  {
    path: '/categories',
    name: 'categories-list',
    component: CategoriesList,
    meta: { requiresAuth: true, title: 'Categories' },
  },
  {
    path: '/brands',
    name: 'brands-list',
    component: BrandsList,
    meta: { requiresAuth: true, title: 'Brands' },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound,
    meta: { layout: 'blank' },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const requiresAuth = to.meta.requiresAuth !== false;
  const isAuthenticated = apiClient.isAuthenticated();

  if (requiresAuth && !isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } });
  } else if (to.name === 'login' && isAuthenticated) {
    next({ name: 'dashboard' });
  } else {
    next();
  }
});

// Update page title
router.afterEach((to) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | Nicola Admin`;
  }
});

export default router;
