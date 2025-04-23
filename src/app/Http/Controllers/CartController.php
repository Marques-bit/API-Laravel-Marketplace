<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cartUser(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $cart = $user->cart;
        return response()->json(['message' => 'Cart retrieved successfully', 'cart' => $cart], 200);
    }

    public function addToCart(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $product = Product::findOrFail($request->product_id);
        $cart = $user->cart;        
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $product->price * $request->quantity,
            ]);
            $cartItem->save();
        }
        return response()->json(['message' => 'Product added to cart successfully', 'cartItem' => $cartItem], 201);
    }

    public function updateQuantity(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $product = Product::findOrFail($request->product_id);
        $cart = $user->cart;
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }
        return response()->json(['message' => 'Quantity updated successfully'], 200);
    }

    public function removeItemFromCart(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $product = Product::findOrFail($request->product_id);
        $cart = $user->cart;
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
        if ($cartItem) {
            $cartItem->quantity -= $request->quantity;
            $cartItem->save();
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
            }
        }
        return response()->json(['message' => 'Product removed from cart successfully'], 200);
    }

    public function removeAllItemsFromCart(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $cart = $user->cart;
        $cartItems = $cart->cartItems;
        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }
        return response()->json(['message' => 'All items removed from cart successfully'], 200);
    }

}
