<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'brand', 'variants', 'images')
            ->where('is_active', true)
            ->paginate(20);

        return $this->paginated($products, 'Products retrieved successfully');
    }

    public function show($product)
    {
        if (is_string($product)) {
            $product = Product::where('slug', $product)->firstOrFail();
        }

        $product->load('category', 'brand', 'variants', 'images', 'reviews');

        return $this->success(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return $this->success(
            new ProductResource($product),
            'Product created successfully',
            201
        );
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $this->success(
            new ProductResource($product),
            'Product updated successfully'
        );
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->success(
            null,
            'Product deleted successfully'
        );
    }

    public function featured()
    {
        $products = Product::with('category', 'brand', 'images')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->limit(10)
            ->get();

        return $this->success(
            ProductResource::collection($products),
            'Featured products retrieved successfully'
        );
    }
}
