<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        })->except(['allCoupons']);
    }

    public function allCoupons()
    {
        $coupons = Coupon::all();
        return response()->json($coupons);
    }

    public function createCoupon(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons',
            'discount_porcentage' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $coupon = Coupon::create($validatedData);

        return response()->json([
            'message' => 'Coupon created successfully',
            'coupon' => $coupon
        ], 201);
    }

    public function updateCoupon(Request $request, $id)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,'.$id,
            'discount_porcentage' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update($validatedData);

        return response()->json([
            'message' => 'Coupon updated successfully',
            'coupon' => $coupon
        ]);
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'message' => 'Coupon deleted successfully'
        ]);
    }

    public function getCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);

        return response()->json([
            'message' => 'Coupon retrieved successfully',
            'coupon' => $coupon
        ]);
    }
}

