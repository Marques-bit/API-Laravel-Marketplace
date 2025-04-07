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
        try {
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

            $validated['user_id'] = Auth::id();

            $address = Address::create($validated);

            return response()->json([
                'message' => 'Address created successfully',
                'address' => $address
            ], 201);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to create address',
        'error' => env('APP_DEBUG') ? $e->getMessage() : null], 500);
        }
    }

    public function addressUpdate(Request $request,$id)
    {
        try {
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

            $address = Address::findOrFail($id);

            if ($address->user_id != Auth::id()) {
                return response()->json(['message' =>
                'You are not authorized to perform this action'], 401);
            }

            $address->update($validated);

            return response()->json([
                'message' => 'Address updated successfully',
                'address' => $address
            ], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update address',
        'error' => env('APP_DEBUG') ? $e->getMessage() : null], 500);
        }
    }

    public function addressDelete(Request $request,$id)
    {
        try {
            $address = Address::findOrFail($id);

            if ($address->user_id != Auth::id()) {
                return response()->json(['message' =>
                'Unauthorized to perform this action'], 401);
            }

            $address->delete();

            return response()->json(['message' => 'Address deleted successfully'], 204);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete address',
        'error' => env('APP_DEBUG') ? $e->getMessage() : null], 500);
        }
    }

}
