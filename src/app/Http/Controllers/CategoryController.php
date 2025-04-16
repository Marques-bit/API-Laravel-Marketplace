<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        })->except(['allCategories']);
    }

    public function allCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function createCategory(Request $request)
    {

        $validateData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'sometimes|string|max:255',
        ]);

        $category = Category::create($validateData);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 200);
    }

    public function updateCategory(Request $request, $id)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'sometimes|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validateData);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

    public function getCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }
}
