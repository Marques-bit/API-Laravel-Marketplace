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
        $request -> validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|string',
            'password_confirm' => 'required|string'
        ]);

        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));

        if ($request->input('password') != $request->input('password_confirm')) {
            return response()->json(['message' => 'Incorrect Password'], 400);
        }

        if ($user->update($request->all())) {
            return response()->json(['message' => 'User updated successfully'], 200);
        }
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
