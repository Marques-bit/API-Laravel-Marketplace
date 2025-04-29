<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'address_id' => 'required|exists:addresses,id|unique:orders',
            'coupon_id' => 'nullable|exists:coupons,id',
        ]);

        $validated['user_id'] = auth()->user()->id;
        $user = User::findOrFail($validated['user_id']);

        $cartItems = $user->cart->items;
        $totalAmount = 0;

        DB::beginTransaction();
        try {
            $order = Order::create($validated);

            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item->product_id);

                if ($product->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }

                $productPrice = $this->productDiscount($item->product_id, $item->price);
                $totalAmount += $productPrice * $item->quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $productPrice,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            if ($validated['coupon_id']) {
                $totalAmount = $this->applyCoupon($totalAmount, $validated['coupon_id']);
            }

            Order::where('id', $order->id)->update([
                'totalAmount' => $totalAmount
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Order creation failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function orderDelete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ], 200);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,completed,canceled'
        ]);

        $order = Order::findOrFail($orderId);

        if (!in_array(Auth::user()->role, ['admin', 'moderator'])) {
            return response()->json(['message' => 'Only admins and moderators can update order status'], 403);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }

    public function applyCoupon($totalAmount, $coupon_id)
    {
        $coupon = Coupon::findOrFail($coupon_id);
        $discountAmount = ($totalAmount * $coupon->discount) / 100;
        $totalAmount -= $discountAmount;
        return $totalAmount;
    }

    public function productDiscount($productId, $originalPrice)
    {
        $totalDiscount = Discount::where('product_id', $productId)
                           ->sum('discount');

        $totalDiscount = min($totalDiscount, 100);


        if ($totalDiscount > 0) {
            return $originalPrice * (1 - ($totalDiscount / 100));
        }

        return $originalPrice;
    }
}
