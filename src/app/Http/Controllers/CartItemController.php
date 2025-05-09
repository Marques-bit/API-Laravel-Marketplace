<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected function getUserCart(User $user)
    {
        return $user->cart()->firstOrFail();
    }

    protected function getCartItem($productId)
    {
        $user = Auth::user()->id;
        $cart = Cart::where('user_id', $user);
        $cartItem = CartItem::where('product_id', $productId)->firstOrFail();
        return $cartItem;
    }

    public function addItemToCart(Request $request)
    {
        $user = Auth::user()->id;
        $cart = Cart::where('user_id', $user)->firstOrCreate();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->items()->create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'price' => Product::find($validated['product_id'])->price
        ]);

        return response()->json([
            'message' => 'Item added to cart successfully'
        ]);
    }

    public function removeItemFromCart(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $cart = $this->getUserCart($user);
        $product = $request->product;
        $quantity = $request->quantity;

        $cartItem = $this->getCartItem($cart, $product->id);

        if ($cartItem) {
            $cartItem->quantity -= $quantity;
            $cartItem->save();

            return response()->json([
                'message' => 'Item removed from cart successfully'
            ]);
        }
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
