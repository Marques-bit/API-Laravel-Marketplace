<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function createAddress(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'street' => 'required|string',
            'number' => 'required|integer',
            'complement' => 'required|string',
            'neighborhood' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);
        
        $user = User::FindOrFail($request->user_id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
            }

        if ($validated['user_id'] != Auth::id()) {
            return response()->json(['message' => 'User not permicioned'], 401);
        }
        

        $address = Address::create($validated);

        return response()->json([
            'message' => 'Address created successfully',
            'address' => $address
        ], 201);
    }

    public function addressUpdate(Request $request,$id)
    {
            $user = Auth::id();

            $validated = $request->validate([
                'user_id' => 'required|integer',
                'street' => 'sometimes|required|string',
                'number' => 'sometimes|required|integer',
                'complement' => 'sometimes|required|string',
                'neighborhood' => 'sometimes|required|string',
                'city' => 'sometimes|required|string',
                'state' => 'sometimes|required|string',
                'country' => 'sometimes|required|string',
            ]);

            if ($validated['user_id'] != Auth::id()) {
                return response()->json(['message' => 'User not permicioned'], 401);
            }

            return response()->json([
                'message' => 'Address updated successfully',
                'address' => $address,
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

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
    public function destroy(string $id)
    {
        //
    }
}
