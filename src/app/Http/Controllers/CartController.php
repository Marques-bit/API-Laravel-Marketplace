<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{

    public function createCart(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        
    }

}
