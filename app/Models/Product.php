<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'description',
        'deleted_at',
        'quantity',
        'price',
        'discount',
        'quantity'
    ];
    public function images()
    {
        return $this->hasMany('App\Models\Image', 'product_id', 'id');
    }
    public function getAllProduct()
    {
        $products = DB::table('products')
            ->leftJoin('images', 'products.id', '=', 'images.product_id')
            ->whereNull('products.deleted_at')
            ->select('products.*', 'images.image_url')
            ->get();
        return $products;
    }
    public function getProductById($id)
    {
        $productDetail = DB::table($this->table)->where('id', $id)->first();
        return $productDetail;
    }

    public function creatNewProduct($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function deleteProductById($id)
    {
        $product = $this->findOrFail($id);
        return $product->delete();
    }
    public function updateProduct($id, $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }

    public static function findById($id)
    {
        return self::find($id);
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'product_id', 'id');
    }

    public function wishLists()
    {
        return $this->hasMany('App\Models\Product', 'product_id', 'id');
    }

    public function subtractQuantity($product_id, $quantityToSubtract)
    {

        $currentQuantity = DB::table($this->table)->where('id', $product_id)->value('quantity');

        if ($currentQuantity >= $quantityToSubtract) {
            $newQuantity = $currentQuantity - $quantityToSubtract;
            DB::table($this->table)->where('id', $product_id)->update(['quantity' => $newQuantity]);
            return true;
        } else {
            return false;
        }
    }


    public function getByKeyword($keyword)
    {
        return DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.product_name', 'LIKE', '%' . $keyword . '%')
            ->select('products.id', 'products.product_name', 'products.category_id', 'products.price', 'products.discount', DB::raw('MAX(images.image_url) as image_url'))
            ->groupBy('products.id', 'products.product_name', 'products.category_id', 'products.price', 'products.discount')
            ->get();
    }
}
