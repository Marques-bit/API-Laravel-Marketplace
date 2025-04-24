<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
    $user = $request->user();
    $user->load('cart.items');

    if (!$user->cart) {
        return response()->json(['message' => 'Cart is empty'
        ], 200);
    }

    return response()->json([
        'id' => $user->cart->id,
        'quantity' => $user->cart->items->sum('quantity'),
        'items' => $user->cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                "unit_price" => $item->product->price,
                ];
            })
        ], 200);
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();
        $user->cart->items()->delete();

        return response()->json(['message' => 'Cart cleared successfully'
        ]);
    }

}
