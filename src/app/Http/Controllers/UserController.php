<?php

namespace App\Http\Controllers;

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
            'password_confirm' => 'required|string'

        ]);

        if ($request->password != $request->password_confirm){
            return response()->json(['message' => 'Inconrrect Password!']);
        }

        $validateData['password'] = Hash::make($validateData['password']);

        $user = User::create($validateData);
        return response()->json([
                'message' => 'Registration Successful',
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

    public function update(Request $request,$id)
    {
        $user = User::findOrFail($id);

        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6|string|confirmed',
        ]);

        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }

        if ($request->has('email')) {
            $user->email = $validatedData['email'];
        }

        if ($request->has('password')) {
            $user->password = Hash::make($validatedData['password']);
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




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    //public function destroy(string $id)
    //{
        //
    //}
}
