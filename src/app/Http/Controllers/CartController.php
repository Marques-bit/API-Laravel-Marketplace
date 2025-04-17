<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function allItemsInCart()
    {
        $carts = Cart::all();
        return response()->json($carts);
    }

    public function createCart(Request $request)
    {
        $validateData = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        
        $cart = Cart::create($validateData);
        
        return response()->json(['message' => 'Cart created successfully', 'cart' => $cart], 201);
    }

    public function updateItemInCart(Request $request, $id)
    {
        $validateData = $request->validate([
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        
        $cart = Cart::findOrFail($id);
        $cart->quantity = $validateData['quantity'];
        $cart->total_price = $validateData['total_price'];
        $cart->save();
        
        return response()->json(['message' => 'Item updated successfully', 'cart' => $cart], 200);
    }
    
    public function addItemToCart(Request $request, $id)
    {
        $validateData = $request->validate([
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        
        $cart = Cart::findOrFail($id);
        $cart->quantity += $validateData['quantity'];
        $cart->total_price += $validateData['total_price'];
        $cart->save();
        
        return response()->json(['message' => 'Item added successfully', 'cart' => $cart], 200);
    }

    public function deleteItemFromCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->quantity -= $request->quantity;
        $cart->total_price -= $request->total_price;
        $cart->save();
        
        return response()->json(['message' => 'Item deleted successfully', 'cart' => $cart], 200);
    }

    public function deleteCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        
        return response()->json(['message' => 'Cart deleted successfully'], 200);
    }
    
    public function getCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        return response()->json(['message' => 'Cart retrieved successfully', 'cart' => $cart], 200);
    }
}
