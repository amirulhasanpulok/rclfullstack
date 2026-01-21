import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import Home from '../pages/Home.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Cart from '../pages/Cart.vue';
import Checkout from '../pages/Checkout.vue';
import Account from '../pages/Account.vue';
import NotFound from '../pages/NotFound.vue';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { title: 'Home' },
  },
  {
    path: '/products/:slug',
    name: 'product-detail',
    component: ProductDetail,
    meta: { title: 'Product' },
  },
  {
    path: '/cart',
    name: 'cart',
    component: Cart,
    meta: { title: 'Shopping Cart' },
  },
  {
    path: '/checkout',
    name: 'checkout',
    component: Checkout,
    meta: { requiresAuth: true, title: 'Checkout' },
  },
  {
    path: '/account',
    name: 'account',
    component: Account,
    meta: { requiresAuth: true, title: 'My Account' },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Update page title
router.afterEach((to) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | Nicola Shop`;
  } else {
    document.title = 'Nicola Shop';
  }
});

export default router;
