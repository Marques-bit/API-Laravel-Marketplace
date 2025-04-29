<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCart(Request $request)
    {

        $cart = Cart::where('user_id', Auth::id())->with('items')->first();

        if ($cart->cart) {
            return response()->json(['message' => 'Cart is empty'
            ], 200);
        }
        
        return response()->json($cart, 200);
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();
        $user->cart->items()->delete();

        return response()->json(['message' => 'Cart cleared successfully'
        ]);
    }

}
