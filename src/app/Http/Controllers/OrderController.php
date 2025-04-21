<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check()||Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        })->except(['allOrders']);
    }

    public function createOrder(Request $request)
    {
        $validateData = $request->validate([
            'user_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::create($validateData);

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }

    public function updateOrder(Request $request, $id)
    {
        $validateData = $request->validate([
            'user_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->update($validateData);

        return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    public function getOrder($id)
    {
        $order = Order::findOrFail($id);
        return response()->json(['message' => 'Order retrieved successfully', 'order' => $order], 200);
    }

}
