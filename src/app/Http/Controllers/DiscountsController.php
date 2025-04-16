<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function allDiscounts()
    {
        $discounts = Discount::all();
        return response()->json($discounts);
    }
    public function createDiscount(Request $request)
    {
        $validateData = $request->validate([
            'product_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount' => 'required|numeric',
        ]);

        $discount = Discount::create($validateData);

        return response()->json(['message' => 'Discount created successfully', 'discount' => $discount], 201);
    }

    public function updateDiscount(Request $request, $id)
    {
        $validateData = $request->validate([
            'product_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount' => 'required|numeric',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update($validateData);

        return response()->json(['message' => 'Discount updated successfully', 'discount' => $discount], 200);
    }

    public function deleteDiscount(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json(['message' => 'Discount deleted successfully'], 200);
    }

    public function getDiscount(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json(['message' => 'Discount retrieved successfully', 'discount' => $discount], 200);
    }
}
