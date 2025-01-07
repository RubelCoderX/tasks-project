<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User signup.
     */
    public function signup(Request $request)
    {
        // Validate request data
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        // Create the user with hashed password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user,
        ], 201); // Use 201 for resource creation
    }

    /**
     * User login.
     */
    public function login(Request $request)
    {
        // Validate request data
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Failed',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        // Attempt to authenticate
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'token' => $authUser->createToken('Api Token')->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email or Password is incorrect',
        ], 401); // 401 for unauthorized
    }

    /**
     * User logout.
     */
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
        ], 200);
    }
}
