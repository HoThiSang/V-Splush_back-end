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
