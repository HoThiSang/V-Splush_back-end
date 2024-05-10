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
}
