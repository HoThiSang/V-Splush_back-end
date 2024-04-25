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
