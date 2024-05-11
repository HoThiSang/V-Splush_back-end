<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "address", "password", "confirmPassword"},
     *             @OA\Property(property="name", type="string", example="Nhã Trần"),
     *             @OA\Property(property="email", type="string", format="email", example="sang@example.com"),
     *             @OA\Property(property="phone", type="string", example="1034567890"),
     *             @OA\Property(property="address", type="string", example="Sơn Trà-Đà Nẵng"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="confirmPassword", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="access_token_here"),
     *             @OA\Property(property="message", type="string", example="Registration successful"),
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Please check your input data"),
     *             @OA\Property(property="status", type="string", example="failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Email already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email already exists"),
     *             @OA\Property(property="status", type="string", example="failed")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10',
            'address' => 'required',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
        ]);
        if (User::where('email', $request->email)->first()) {
            return response()->json([
                'message' => 'Email already exists',
                'status' => 'failed'
            ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role_id' => 1,
        ]);

        $accessToken = $user->createToken($request->name)->plainTextToken;
        return response()->json([
            'token' => $accessToken,
            'message' => 'Registration Success',
            'status' => 'success'
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login data",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="sang@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="access_token_here"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid login credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid login credentials"),
     *             @OA\Property(property="status", type="string", example="failed")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $accessToken = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'token' => $accessToken,
                'message' => 'Login Success',
                'status' => 'success'
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid Credentials',
            'status' => 'failed'
        ], 401);
    }


    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout successful"),
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Success',
            'status' => 'success'
        ], 200);
    }
}
