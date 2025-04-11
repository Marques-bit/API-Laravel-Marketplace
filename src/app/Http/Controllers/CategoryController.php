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
            if (Auth::user()->role != 'admin') {
                return response()->json(['message' => 'Unauthorized'], 401);
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
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'sometimes|string|max:255',
        ]);

        $category = Category::create(['name' => $request->name]);
        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'sometimes|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return response()->json($category);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function getCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }
}
