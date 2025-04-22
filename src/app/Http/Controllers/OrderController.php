<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthorized - Please login'], 401);
            }

            $user = Auth::user();

            if (!in_array($user->role, ['user', 'admin', 'moderator'])) {
                return response()->json(['message' => 'Forbidden - Insufficient privileges'], 403);
            }

            return $next($request);
        });

    }

    public function createOrder(Request $request)
{
    $validated = $request->validate([
        'address_id' => 'required|exists:addresses,id',
        'coupon_id' => 'nullable|exists:coupons,id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1'
    ]);

    $user = auth()->user();

    $totalAmount = 0;
    $orderItems = [];

    foreach ($validated['items'] as $item) {
        $product = Product::findOrFail($item['product_id']);
        $subtotal = $product->price * $item['quantity'];
        $totalAmount += $subtotal;

        $orderItems[] = [
            'product_id' => $product->id,
            'quantity' => $item['quantity'],
            'unit_price' => $product->price,
            'subtotal' => $subtotal
        ];
    }

    if (!empty($validated['coupon_id'])) {
        $coupon = Coupon::findOrFail($validated['coupon_id']);
        $totalAmount = $coupon->applyDiscount($totalAmount);
    }

    $order = $user->orders()->create([
        'user_id' => $user->id,
        'address_id' => $validated['address_id'],
        'order_date' => now(),
        'coupon_id' => $validated['coupon_id'] ?? null,
        'status' => 'pending',
        'totalAmount' => $totalAmount
    ]);

    $order->items()->createMany($orderItems);

    return response()->json([
        'message' => 'Order created successfully',
        'order' => $order->load(['items.product', 'address', 'coupon'])
    ], 201);
}

    public function orderDelete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
