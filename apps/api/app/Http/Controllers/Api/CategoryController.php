<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with('products')
            ->get();

        return $this->success(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    public function show($category)
    {
        if (is_string($category)) {
            $category = Category::where('slug', $category)->firstOrFail();
        }

        $category->load('products');

        return $this->success(
            new CategoryResource($category),
            'Category retrieved successfully'
        );
    }
}
