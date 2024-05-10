<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class UserCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $carts;
    protected $product;

    public function  __construct(){
        $this->carts = new Cart();
        $this->product = new Product();
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // if (Auth()->check()) {
            $product_id = $request->input('id');
            $quantity = 1;
            // $user_id = Auth()->user()->id;
            $user_id = 2;


            $existing_cart_item = Cart::where('product_id', $product_id)
                ->where('user_id', $user_id)
                ->first();
            if ($existing_cart_item) {
                $existing_cart_item->quantity = $existing_cart_item->quantity + 1;
                $existing_cart_item->total_price = number_format($existing_cart_item->unit_price * $existing_cart_item->quantity, 2, '.', '');

                $existing_cart_item->save();
                // return redirect()->back()->with('success', 'The product has been added to the cart.');
                return response()->json([
                    "status" =>"success",
                    "message" =>"The product has been added to cart.",
                    "data" =>$existing_cart_item,200]);
            } else {
                $product = $this->product->getProductByIDs( $product_id );

                if ($product) {
                    $cart_item = new Cart();
                    $cart_item->product_id = $product_id;
                    $cart_item->quantity = $quantity;
                    $cart_item->user_id =  $user_id ;
                    // $cart_item->price = $product->discounted_price;
                    $cart_item->unit_price = $product->price;
                    $cart_item->total_price = $product->price * $cart_item->quantity ;
                    $cart_item->save();
                    return response()->json([
                        "status" =>"success",
                        "message" =>"The product has been added to cart.",
                        "data" =>$cart_item,200]);
                    // return redirect()->back()->with('success', 'The product has been added to cart.');
                } else {
                    return response()->json([
                        "status"=>"error",
                        "message" => "No product information found.",
                    ],500);
                    // return redirect()->back()->with('error', 'No product information found.');
                }
            }
        // }else{
        //     return redirect()->back()->with('error', 'Not found user');
        // }
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