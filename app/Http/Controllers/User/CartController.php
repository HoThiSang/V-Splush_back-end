<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    protected $cart;
    protected $product;


    public function __construct()
    {
        $this->cart = new Cart();
        $this->product = new Product();
    }
    public function showCart()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $check = 'error';
        // $total_price = 0;
        if (auth()->check()) {
            $user_id = Auth::id();
            $carts = $this->cart->getAllCarts($user_id);
            // foreach ($carts as $cart) {
            //     $total_price += $cart->total_price;
            // }
            // dd($total_price);
            $count = count($carts);
            $check = 'success';
            return response()->json([
                'status' => $check,
                'count' => $count,
                'data' => $carts
            ]);
        }
        return response()->json([
            'status' => $check,
            'message' => 'User is not authenticated'
        ], 401);
    }
    /**
     * @OA\Post(
     *     path="/api/add-to-cart",
     *     summary="add to cart",
     *     tags={"Add to cart"},
     *  @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="Product Id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */


    public function addToCart(Request $request)
    {
        //
        if (Auth()->check()) {


            if ($request->isMethod('post')) {
                $product_id = $request->product_id;
                $quantity = $request->quantity;

                $user = Auth()->user();
                $user_id = $user->id;
                $existing_cart_item = Cart::where('product_id', $product_id)
                    ->where('user_id', $user_id)
                    ->first();
                if ($existing_cart_item) {
                    $existing_cart_item->quantity = $existing_cart_item->quantity +  $request->quantity;
                    $product = $this->product->getProductById($product_id);
                    $discounted_price = $product->price - ($product->price * ($product->discount / 100));
                    $existing_cart_item->total_price = $discounted_price * $existing_cart_item->quantity;
                    $existing_cart_item->save();
                    return response()->json([
                        "status" => "success",
                        "message" => "The product has been added to cart.",
                        "data" => $existing_cart_item
                    ], 200);
                } else {


                    $product = $this->product->getProductById($product_id);
                    if ($product) {
                        $cart_item = new Cart();
                        $cart_item->product_id = $product_id;
                        $cart_item->quantity = $quantity;
                        $cart_item->user_id =  $user_id;
                        $cart_item->unit_price = $product->price;
                        $cart_item->total_price = $product->price - ($product->price * ($product->discount / 100)) * $cart_item->quantity;
                        $cart_item->save();
                        return response()->json([
                            "status" => "success",
                            "message" => "The product has been added to cart.",
                            "data" => $cart_item
                        ], 200);
                    } else {
                        return response()->json([
                            "status" => "error",
                            "message" => "No product information found.",
                        ], 500);
                    }
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The method not post',
                ]);
            }
        }
    }



    public function updateCart(Request $request)
    {

        if (Auth()->check()) {
            $user = Auth::user();
            $user_id = auth()->id();
            $quantity = $request->quantity;
            $product_id = $request->product_id;
            if ($product_id) {
                $cartItem = $this->cart->findItemById($product_id, $user_id);
                if ($cartItem) {
                    $productPrice = $cartItem->unit_price;
                    $productQuantity = $cartItem->quantity;
                    $newQuantity = $quantity + $productQuantity;
                    $newPrice = $productPrice * $newQuantity;
                    $cartItem->update([
                        'quantity' => $newQuantity,
                        'total_price' => $newPrice,
                    ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Quantity has been updated.',
                        'data' => $cartItem,
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in the cart.',
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Missing product ID in the request.',
            ]);
        }
    }

    public function updateCartSubtract(Request $request)
    {

        if (Auth()->check()) {
            $user = Auth::user();
            $user_id = $user->id;
            $quantity = $request->quantity;
            $product_id = $request->product_id;
            if ($product_id) {
                $cartItem = $this->cart->findItemById($product_id, $user_id);
                if ($cartItem) {
                    $productPrice = $cartItem->unit_price;
                    $productQuantity = $cartItem->quantity;
                    $newQuantity = $productQuantity -  $quantity;
                    if ($newQuantity === 0) {
                        $cartItem->delete();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Product has been deleted in cart.',
                        ]);
                    }
                    $newPrice = $productPrice * $newQuantity;
                    $cartItem->update([
                        'quantity' => $newQuantity,
                        'total_price' => $newPrice,
                    ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Quantity has been updated.',
                        'data' => $cartItem,
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in the cart.',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Missing product ID in the request.',
            ]);
        }
    }

    public function deleteCart($product_id)
    {
        if (Auth()->check()) {
            $user = Auth::user();
            $user_id = $user->id;
            $userExists = User::where('id', $user_id)->exists();
            if (!$userExists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 404);
            }
            $result = $this->cart->deleteByProductId($product_id, $user_id);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product has been removed from the cart.',
                    'data' => $result
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in the cart.',
                    'data' => $result
                ], 404);
            }
        }
    }
}
