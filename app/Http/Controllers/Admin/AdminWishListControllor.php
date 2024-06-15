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
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/show-allwishlist",
     *     summary="Get all wish lists",
     *     tags={"Wish list"},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="500", description="Error"),
     *     @OA\Response(response="404", description="Wish list not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        try {
            $allWishList = $this->wishlist->getAllWishList();

            if (!empty($allWishList)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Show all wish lists successfully',
                    'data' => $allWishList
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No wishlists found',
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error show wishlists: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed show wishlists',
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
     *
     * @OA\Delete(
     *     path="/api/delete-wish-list/{id}",
     *     summary="Delete a wish list by ID",
     *     tags={"Wish list"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the wish list to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="wish list deleted successfully"),
     *     @OA\Response(response="404", description="wish list not found"),
     *     @OA\Response(response="500", description="Failed to delete wish list"),
     *     @OA\Response(response="400", description="Invalid wish list ID")
     * )
     */
    public function destroy(string $id)
    {
        if (!empty($id)) {
            $wishList = WishList::find($id);
            if ($wishList) {
                $deleted = $wishList->deleteWishList($id);

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
