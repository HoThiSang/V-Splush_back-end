<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' =>'required|email|unique:users',
            'phone' => 'required|digits:10',
            'address' => 'required',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
        ]);
        if(User::where('email', $request->email)->first()){
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

    public function login(Request $request){
        $request->validate([
            'email' =>'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)){
            $accessToken = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'token' => $accessToken,
               'message' => 'Login Success',
               'status' =>'success'
            ], 200);
        }
        return response()->json([
           'message' => 'Invalid Credentials',
           'status' => 'failed'
        ], 401);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
           'message' => 'Logout Success',
           'status' =>'success'
        ], 200);
    }
    
}
