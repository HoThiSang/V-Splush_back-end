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


class ProductController extends Controller
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
       
    }
    public function saveImage(Request $request, $product_id, $url)
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
        $product = $this->products->findById($id);
        if (!empty($product)) {

            if ($request->isMethod('post')) {

                if ($request->hasFile('images')) {

                    // $image = $request->file('images');
                    // $imageName = time() . '_' . $image->getClientOriginalName();
                    // $image->move(public_path('images'), $imageName);

                    $dataInsert = [
                        'product_name' => $request->product_name,
                        'quantity' => $request->quantity,
                        'price' => $request->price,
                        'ingredient' => $request->ingredient,
                        'description' => $request->description,
                        'brand' => $request->brand,
                        'discount' => $request->discount,
                        'discounted_price' => 30000,
                        'category_id' => $request->category_id,
                        'updated_at' => now()
                    ];
                    $product_id = $this->products->updateProduct($id, $dataInsert);

                    if ($product_id > 0) {
                        $imageSuccess = true;
                        $successCount = 0;
                        $imageData = [];
                        foreach ($request->file('images') as $image) {
                            $imageName = $image->getClientOriginalName();
                            $image->move(public_path('images'), $imageName);
                            $imageData = [
                                'image_name' => $request->product_name,
                                'image_url' => $imageName,
                                'product_id' => $product_id,
                                'updated_at' => now()
                            ];
                            $images = $this->image->updateImage($product_id, $imageData);

                            if ($imageData) {
                                $successCount++;
                            }
                        }

                        // Lưu trữ dữ liệu ảnh vào cơ sở dữ liệu


                        if ($successCount == count($request->file('images'))) {
                            return redirect()->route('product-index')->with('success', 'All images added successfully');
                        } else {
                            return redirect()->route('product-index')->with('error', 'Some images failed to add');
                        }
                        if ($imageSuccess) {
                            return redirect()->route('product-index')->with('success', 'Product added successfully');
                        } else {
                            return redirect()->route('product-index')->with('error', 'Failed to add Image');
                        }
                    } else {
                        return redirect()->route('product-index')->with('error', 'Failed to add product');
                    }
                } else {
                    return redirect()->back()->with('error', 'Missing image fields');
                }
            } else {
                return redirect()->back()->with('error', 'Missing required fields');
            }
        }
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
