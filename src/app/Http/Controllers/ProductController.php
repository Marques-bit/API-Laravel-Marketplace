<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check()||Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        })->except(['allProducts']);
    }

    public function allProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function createProduct(Request $request)
    {
        $validateData = $request->validate([
            'name'        => 'required|string|max:255|unique:products',
            'description' => 'sometimes|string|max:255',
            'price'      => 'required|decimal:10,2',
            'image'       => 'required|image|max:1024',
            'category_id' => 'required|numeric',
            'discount_id' => 'sometimes|numeric',
            'quantity'    => 'required|numeric',
        ]);

        $product = Product::create($validateData);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);

    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:products',
            'description' => 'sometimes|string|max:255',
            'price'      => 'required|decimal:10,2',
            'image'       => 'required|image|max:1024',
            'category_id' => 'required|numeric',
            'discount_id' => 'sometimes|numeric',
            'quantity'    => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validateData);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['message' => 'Product retrieved successfully', 'product' => $product], 200);
    }
}
