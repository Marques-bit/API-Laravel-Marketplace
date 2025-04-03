<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validate;
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
            'password_confirm' => 'required|string'

        ]);
        if ($request->password != $request->password_confirm){
            return response()->json(['message' => 'Inconrrect Password!']);
        }
        $validateData['password'] = Hash::make($validateData['password']);
        $user = User::create($validateData);
            return response()->json([
                'message' => 'Registration Successful',
                'token' => $token
        ], 201);

    }

    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $request->user();

            $token = $user->createToken('Personal Access Token');
            return response()->json([
                'message' => 'Login Successful',
                'token' => $token
            ], 200);
        }
        return response()->json(['message' => 'Invalid Credentials'], 401);

    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
           return response()->json(['message' => 'User not found'], 404);
        }

        if (!auth()->user()->can('delete', $user)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
        //


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    //public function destroy(string $id)
    //{
        //
    //}
}
