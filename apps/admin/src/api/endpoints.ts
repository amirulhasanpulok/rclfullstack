import { apiClient } from './client';

export interface Product {
  id: number;
  name: string;
  slug: string;
  description: string;
  base_price: number;
  category_id: number;
  brand_id: number;
  is_active: boolean;
  is_featured?: boolean;
  rating?: number;
  created_at?: string;
  updated_at?: string;
  category?: any;
  brand?: any;
  variants?: any[];
  images?: any[];
  reviews?: any[];
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  description?: string;
  parent_id?: number;
  is_active: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface Brand {
  id: number;
  name: string;
  slug: string;
  logo_url?: string;
  is_active: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data?: T;
  meta?: {
    timestamp: string;
    version: string;
  };
}

export interface PaginatedResponse<T> {
  success: boolean;
  message: string;
  data: T[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
    timestamp: string;
    version: string;
  };
}

export const productsApi = {
  list: (page = 1, perPage = 20) =>
    apiClient.get<PaginatedResponse<Product>>('/products', {
      params: { page, per_page: perPage },
    }),

  featured: () =>
    apiClient.get<ApiResponse<Product[]>>('/products/featured'),

  get: (id: number | string) =>
    apiClient.get<ApiResponse<Product>>(`/products/${id}`),

  create: (data: Partial<Product>) =>
    apiClient.post<ApiResponse<Product>>('/products', data),

  update: (id: number, data: Partial<Product>) =>
    apiClient.put<ApiResponse<Product>>(`/products/${id}`, data),

  delete: (id: number) =>
    apiClient.delete<ApiResponse<void>>(`/products/${id}`),
};

export const categoriesApi = {
  list: () =>
    apiClient.get<ApiResponse<Category[]>>('/categories'),

  get: (id: number | string) =>
    apiClient.get<ApiResponse<Category>>(`/categories/${id}`),

  create: (data: Partial<Category>) =>
    apiClient.post<ApiResponse<Category>>('/categories', data),

  update: (id: number, data: Partial<Category>) =>
    apiClient.put<ApiResponse<Category>>(`/categories/${id}`, data),

  delete: (id: number) =>
    apiClient.delete<ApiResponse<void>>(`/categories/${id}`),
};

export const brandsApi = {
  list: () =>
    apiClient.get<ApiResponse<Brand[]>>('/brands'),

  get: (id: number | string) =>
    apiClient.get<ApiResponse<Brand>>(`/brands/${id}`),

  create: (data: Partial<Brand>) =>
    apiClient.post<ApiResponse<Brand>>('/brands', data),

  update: (id: number, data: Partial<Brand>) =>
    apiClient.put<ApiResponse<Brand>>(`/brands/${id}`, data),

  delete: (id: number) =>
    apiClient.delete<ApiResponse<void>>(`/brands/${id}`),
};
