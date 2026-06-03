<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index(): JsonResponse
    {
        $query = Product::with(['user', 'likes', 'category']);

        if (request()->has('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->latest()->get(),
        ]);
    }

    public function categories(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => Category::all(),
        ]);
    }

    public function store(ProductsRequest $request): JsonResponse
    {
        $product = $request->user()->products()->create($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Product created',
            'data'    => $product->load(['user', 'likes', 'category'])
        ], 201);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ((int) $product->user_id !== (int) Auth::id()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Forbidden: You do not own this product'
            ], 403);
        }

        $product->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
}
