<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        })->except(['allProducts', 'getProduct']);
    }

    public function allProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function createProduct(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'description' => 'sometimes|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|numeric',
            'stock' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validateData['image'] = $imagePath;
        }

        $product = Product::create($validateData);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
            'image_url' => asset("storage/{$product->image}")
        ], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validateData = $request->validate([
            'name' => 'sometimes|string|max:255|unique:products,name,'.$id,
            'description' => 'sometimes|string|max:255',
            'price' => 'sometimes|decimal:10,2',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
            'stock' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validateData['image'] = $imagePath;
        }

        $product->update($validateData);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
            'image_url' => asset("storage/{$product->image}")
        ], 200);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'message' => 'Product retrieved successfully',
            'product' => $product,
            'image_url' => $product->image ? asset("storage/{$product->image}") : null
        ], 200);
    }
}
