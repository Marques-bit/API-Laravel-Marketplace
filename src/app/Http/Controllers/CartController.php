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

    return new CartResource($user->cart);
    }

    public function deleteCart(Request $request)
    {
    $user = $request->user();

    if (!$user->cart) {
        return response()->json([
            'message' => 'Cart not found'
        ], 404);
    }

    $this->authorize('delete', $user->cart);
    $user->cart()->delete();

    return response()->json([
        'message' => 'Cart deleted successfully'
    ], 204);
    }

}
