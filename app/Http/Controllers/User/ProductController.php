<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $products;

    public function __construct()
    {
        $this->products = new Product();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productAll = $this->products->getTopDiscountedProducts();
        if ($productAll) {
            return response()->json([
                'status' => 'success',
                'message' => 'Get all product successfully',
                'data' => $productAll
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
    }

     /**
     * @OA\Get(
     *     path="/api/search-product/{keyword}",
     *     summary="Search product by keyword",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="path",
     *         required=true,
     *         description="keyword of the product to search",
     *    @OA\Schema(
     *             type="string",
     *
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Product not found")
     * )
     */
    public function search(Request $request, $keyword)
    {

        $productSearch =$this->products->getByKeyWord($keyword);
        if(!empty($productSearch)){
            return response()->json([
                'message' => 'Search product Success',
                'status' => 'success',
                'data'=> $productSearch
            ], 200);
        }else{
            return response()->json([
                'message' => 'Not found product with name '. $keyword,
                'status' => 'error',
            ], 404);
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
        //
    }

    public function getPopularProduct() {
        $popularProduct = $this->products->getPoplurProduct();
        if(!empty($popularProduct)){
            return response()->json([
                'message' => 'Search product Success',
                'status' => 'success',
                'data'=> $popularProduct
            ], 200);
        }else{
            return response()->json([
                'message' => 'Not found product ',
                'status' => 'error',
            ], 404);
        }
    }

}
