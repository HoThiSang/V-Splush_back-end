<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use App\Models\Slide;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class AdminProductController extends Controller
{
    protected $products;
    protected $image;
    protected $categories;

    public function __construct()
    {
        $this->products = new Product();
        $this->categories = new Category();
        $this->image = new Image();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/admin-product",
     *     summary="Get all products",
     *     tags={"Product"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()

    {
        $productAll = $this->products->getAllProduct();
        return $productAll;
    }
    /**
     * @OA\Get(
     *     path="/api/admin-product-detail/{id}",
     *     summary="Detail a product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to detail",
     *    @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Comment not found")
     * )
     */
    public function show($id)
    {
        $productDetail = $this->products->getProductById($id);
        if (!$productDetail) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $imageAll = $this->image->getAllImageByProductId($id);
        $category_id = $productDetail->category_id;
        $category = $this->categories->getCategoryById($category_id);

        return response()->json([
            'status' => 'success',
            'productDetail' => $productDetail,
            'imageAll' => $imageAll,
            'category' => $category
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *     path="/api/admin-add-product",
     *     summary="Create a new product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="product_name",
     *         in="query",
     *         description="Name of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Price of the product",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="discount",
     *         in="query",
     *         description="Discount on the product",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="quantity",
     *         in="query",
     *         description="Quantity of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Description of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="ingredient",
     *         in="query",
     *         description="Ingredients of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="brand",
     *         in="query",
     *         description="Brand of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image_url[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary"),
     *                     description="Images of product"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Add new product successfully"),
     *     @OA\Response(response="422", description="Validation errors"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function store(ProductRequest $request)
    {
        if ($request->isMethod('post')) {
            if (
                isset($request->product_name) && isset($request->price) && isset($request->discount)
                && isset($request->quantity) && isset($request->description) && isset($request->ingredient)
                && isset($request->brand) && isset($request->category_id)
            ) {

                if ($request->hasFile('image_url')) {
                    $dataInsert = [
                        'product_name' => $request->product_name,
                        'quantity' => $request->quantity,
                        'price' => $request->price,
                        'ingredient' => $request->ingredient,
                        'description' => $request->description,
                        'brand' => $request->brand,
                        'discount' => $request->discount,
                        'category_id' => $request->category_id,
                        'created_at' => now()
                    ];
                    $product_id = $this->products->creatNewProduct($dataInsert);
                    if ($product_id > 0) {
                        $files = $request->file('image_url');
                        $uploadedImages = [];
                        $imageSuccess = true;
                        foreach ($files as $file) {
                            $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                                'folder' => 'upload_image'
                            ])->getSecurePath();
                            $publicId = Cloudinary::getPublicId();
                            $extension = $file->getClientOriginalName();
                            $filename = time() . '_' . $extension;

                            $dataImage = [
                                'image_name' => $request->product_name,
                                'image_url' =>  $uploadedFileUrl,
                                'product_id' => $product_id,
                                'publicId' => $publicId,
                                'created_at' => now()
                            ];
                            $imageSuccess = $this->image->createImageByProductId($dataImage);
                        }
                        if ($imageSuccess) {
                            $uploadedImages[] = $imageSuccess;
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Add new  product successfully',
                                'data' => $product_id
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Add image product field',
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Failed to add product',
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Missing image fields',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Missing required fields',
                ], 500);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Add new category field',
        ], 500);
    }

    // public function saveImage(ProductRequest $request, $product_id, $url)
    // {
    //     $request->validate([
    //         'url' => 'required|url',
    //     ]);
    //     $this->image->url = $request->url;
    // }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/api/admin-product-update/{id}",
     *     summary="Update product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="product_name",
     *         in="query",
     *         description="Name of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Price of the product",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="discount",
     *         in="query",
     *         description="Discount on the product",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="quantity",
     *         in="query",
     *         description="Quantity of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Description of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="ingredient",
     *         in="query",
     *         description="Ingredients of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="brand",
     *         in="query",
     *         description="Brand of the product",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image_url[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary"),
     *                     description="Updated images of the product"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product updated successfully"),
     *     @OA\Response(response="404", description="Product not found"),
     *     @OA\Response(response="422", description="Validation errors"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function update(ProductRequest $request, $id)
    {
        $product = $this->products->findById($id);

        if (empty($product)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        if (!$request->isMethod('post')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request method',
            ], 405);
        }

        $dataUpdate = [
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'ingredient' => $request->ingredient,
            'description' => $request->description,
            'brand' => $request->brand,
            'discount' => $request->discount,
            'category_id' => $request->category_id,
            'updated_at' => now()
        ];
        $updatedProduct = $this->products->updateProduct($id, $dataUpdate);

        if (!$updatedProduct) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
            ], 500);
        }
        $uploadedImages = [];

        if ($request->hasFile('image_url')) {
            $images = $this->image->getImageByIdProduct($id);

            $i = 0;
            foreach ($request->file('image_url') as $file) {
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'upload_image'
                ])->getSecurePath();

                $publicId = Cloudinary::getPublicId();

                $dataImage = [
                    'image_name' => $request->product_name,
                    'image_url' =>  $uploadedFileUrl,
                    'product_id' => $id,
                    'publicId' => $publicId,
                    'created_at' => now()
                ];
                $imageId = $images[$i]->id;
                $uploadedImages[] = $dataImage;

                $this->image->updateImage($imageId, $dataImage);
                $i++;
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *     path="/api/admin-product-delete/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product deleted successfully"),
     *     @OA\Response(response="404", description="Product not found")
     * )
     */
    public function destroy($id)
    {
        if (!empty($id)) {
            $product = $this->products->getProductById($id);
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }
            $images = $this->image->getAllImageByProductId($id);
            foreach ($images as $image) {
                Cloudinary::destroy($image->publicId);
            }
            $this->image->deleteImagesByProductId($id);
            $deleted = $this->products->deleteProductById($id);
            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete product'
                ], 500);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Product ID is required'
        ], 400);
    }
}
