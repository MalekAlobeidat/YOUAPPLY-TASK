<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
    public function forgetPassword(Request $request){
        $request->validate([
            'phone_number' => ['required', 'regex:/^(079|077|078)[0-9]{7}$/','size:10'],
            'token' => 'required|size:5',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::with('resetPassword')->where('phone_number', $request->phone_number)->first();

        // dd($user->resetPassword);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Assuming you have a column named 'password_reset_token' in your users table
        if ($user->resetPassword->token !== $request->token) {
            return response()->json(['message' => 'Invalid token'], 400);
        }
        $user->password = Hash::make($request->password);
        $user->resetPassword->token = null; // Clear the password reset token
        $user->resetPassword->save(); 
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
