<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishList;
use Illuminate\Support\Facades\Log;

class AdminWishListControllor extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $wishlist;

    public function __construct(WishList $wishlist)
    {
        $this->wishlist = $wishlist;
    }

    public function index()
    {
        try {
            $allWishList = $this->wishlist->getAllWishList();

            if (!empty($allWishList)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Wishlists retrieved successfully',
                    'data' => $allWishList
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No wishlists found',
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving wishlists: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve wishlists',
            ], 500);
        }
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
        if (!empty($id)) {
            $wishList = WishList::find($id);
            if ($wishList) {
                $deleted = $wishList->deleteWishListById($id);

                if ($deleted) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Deleted wish list successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to delete wish list',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wish list not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid wish list ID',
            ], 400);
        }
    }
}
