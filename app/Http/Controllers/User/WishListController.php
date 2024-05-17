<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishList;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{

    protected $wishlist;

    public function __construct(WishList $wishlist)
    {
        $this->wishlist = $wishlist;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/create-wishlist",
     *     summary="Create a new wish list",
     *     tags={"Wish list"},
     *     @OA\Parameter(
     *         name="product_id",
     *         in="query",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Product added to wishlist successfully"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="500", description="Internal Server Error"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $userId = 1;
            $productId = $request->input('product_id');
            if (empty($productId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product ID is required',
                ], 400);
            }
            $wishList = $this->wishlist->createWishList($userId, $productId);
            if ($wishList) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully added the product to the wishlist',
                    'data' => $wishList
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add the product to the wishlist',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid method',
            ], 405);
        }
    }
}
