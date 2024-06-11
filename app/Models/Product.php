<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'description',
        'quantity',
        'price',
        'discount',
        'quantity'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const DELETED_AT = 'deleted_at';

    public function images()
    {
        return $this->hasMany('App\Models\Image', 'product_id', 'id');
    }
    public function getAllProduct($perPage = null)
    {
        $products = DB::table('products')
            ->leftJoin('images', 'products.id', '=', 'images.product_id')
            ->whereNull('products.deleted_at')
            ->select('products.*', DB::raw('GROUP_CONCAT(images.image_url) AS image_urls'))
            ->groupBy('products.id')
            ->orderBy('products.id')
            ->get();

        $productsWithFirstImage = $products->map(function ($product) {
            $imageUrls = explode(',', $product->image_urls);
            $product->image_url = $imageUrls[0] ?? null;
            return $product;
        });

        return $productsWithFirstImage;
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
        return   DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->where('products.product_name', 'LIKE', '%' . $keyword . '%')
            ->select('products.id', 'products.product_name',  'products.price', 'products.discount', DB::raw('MAX(images.image_url) as image_url'))
            ->groupBy('products.id')
            ->orderBy('products.id')
            ->get();
    }

    public function getPoplurProduct()
    {
        return DB::table('products')
            ->leftJoin('images', 'products.id', '=', 'images.product_id')
            ->where('discount', '>', 0)
            ->whereNull('products.deleted_at')
            ->select('products.*', 'images.image_url')
            ->orderBy('created_at', 'desc') // hoặc orderBy('sold_count', 'desc')
            ->limit(3)
            ->get();
    }
}
