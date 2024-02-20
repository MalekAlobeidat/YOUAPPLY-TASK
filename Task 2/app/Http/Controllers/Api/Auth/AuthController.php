<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'regex:/^(079|077|078)[0-9]{7}$/','size:10'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name_en' => $validatedData['phone_number'],
            'phone_number' => $validatedData['phone_number'],
            'password' => Hash::make($validatedData['password']),
            'verification_code' => '1234',
        ]);
        $reset_password = new ResetPasswordController();
        $reset_password->store($request);
        return response()->json(['message' => 'Account Created Successfully Please verify to login'], 201);
    }

    public function verifyPhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'regex:/^(079|077|078)[0-9]{7}$/'],
            'verification_code' => ['required', 'digits:4', 'numeric'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->phone_verified) {
            return response()->json(['message' => 'user phone is verified try to login '], 200);
        }
        // Verify hashed verification code
        if ($request->verification_code === $user->verification_code) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        // Update user record to mark phone number as verified
        $user->update([
            'phone_verified' => true,
            'verification_code' => null, // Optionally, clear the hashed verification code
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Verified Successfully ','token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => ['required', 'regex:/^(079|077|078)[0-9]{7}$/'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->phone_verified) {
                return response()->json(['message' => 'user phone must be verified first '], 403);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }



}
