<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected function getAuthenticatedUser()
    {
        return $request->user();
    }

    protected function getUserCart(User $user)
    {
        return $user->cart()->firstOrFail();
    }

    protected function getCartItem(Cart $cart, $productId)
    {
        return $cart->cartItems()->where('product_id', $productId)->first();
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $this->getAuthenticatedUser();
        $cart = $this->getUserCart($user);
        $product = Product::findOrFail($request->product_id);

        $cartItem = $this->getCartItem($cart, $product->id);

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
            $cartItem->total_price = $product->price * $cartItem->quantity;
            $cartItem->save();
        } else {
            $cartItem = $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $product->price * $request->quantity,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully',
            'cartItem' => $cartItem
        ], 201);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $this->getAuthenticatedUser();
        $cart = $this->getUserCart($user);
        $cartItem = $this->getCartItem($cart, $request->product_id);

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'total_price' => $cartItem->product->price * $request->quantity
        ]);

        return response()->json([
            'message' => 'Quantity updated successfully',
            'cartItem' => $cartItem
        ]);
    }

    public function removeItemFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $this->getAuthenticatedUser();
        $cart = $this->getUserCart($user);
        $cartItem = $this->getCartItem($cart, $request->product_id);

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        if ($cartItem->quantity <= $request->quantity) {
            $cartItem->delete();
            $message = 'Product removed from cart completely';
        } else {
            $cartItem->decrement('quantity', $request->quantity);
            $cartItem->total_price = $cartItem->product->price * $cartItem->quantity;
            $cartItem->save();
            $message = 'Product quantity reduced successfully';
        }

        return response()->json(['message' => $message]);
    }

    public function removeAllItemsFromCart(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $cart = $this->getUserCart($user);

        $cart->cartItems()->delete();

        return response()->json([
            'message' => 'All items removed from cart successfully'
        ]);
    }
}
