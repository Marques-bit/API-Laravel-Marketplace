<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|string',
            'password_confirm' => 'required|string',

        ]);

        if ($request->password != $request->password_confirm){
            return response()->json(['message' => 'Inconrrect Password!'],400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user = Cart::create([
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Registration Successful',
            'user' => $user
        ], 201);

    }

    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $request->user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'message' => 'Login Successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
        }
        return response()->json(['message' => 'Invalid Credentials'], 401);


    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validateData = $request->validate([
            'name' => 'sometimes|string|max:255|unique:users,name,'.$user->id,
            'email' => 'sometimes|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6|string|confirmed',
        ]);

        if ($request->has('name')) {
            $user->name = $validateData['name'];
        }

        if ($request->has('email')) {
            $user->email = $validateData['email'];
        }

        if ($request->has('password')) {
            $user->password = Hash::make($validateData['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

    public function deleteUser()
    {
        $user = User::findorFail(Auth::id());

        $user=Auth::user();

        if (!$user) {
           return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
