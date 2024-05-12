<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cart;
    public function __construct()
    {
        $this->cart = new Cart();
    }
    public function showCart()
    {
        $check = 'error';
        if (Auth()->check()) {
            $user_id = Auth::id();
            $carts = $this->cart->getAllCarts($user_id);
            $check = 'success';
            return response()->json([
                'status' => $check,
                'carts' => $carts
            ]);
        }
        return response()->json([
            'status' => $check,
            'message' => 'User is not authenticated'
        ], 401);
    }
}