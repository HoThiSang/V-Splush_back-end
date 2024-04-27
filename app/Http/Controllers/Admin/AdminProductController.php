<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
// use Illuminate\Http\Request;
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
     */    public function index()

    {
        $productAll = $this->products->getAllProduct();
        return $productAll;
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
                                'data' => $imageSuccess
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

    public function saveImage(ProductRequest $request, $product_id, $url)
    {
        $request->validate([
            'url' => 'required|url',
        ]);
        $this->image->url = $request->url;
    }


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
    public function update(ProductRequest $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!empty($id)) {
            $product = $this->products->deleteProductById($id);
            return redirect()->route('product-index')->with('success', 'Product deleted successfully');
        }
        return redirect()->back()->with('error', 'Product deleted fields');
    }
}
