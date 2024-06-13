<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function getAllCart($user_id)
    {
        $cart = DB::table('carts')->where('user_id', $user_id)->get();
        return $cart;
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // // Định nghĩa relationship với User
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function getAllCarts($user_id)
    {
        return DB::table('carts')
            ->select(
                'carts.id',
                'products.product_name',
                'carts.total_price',
                'products.price as unit_price',
                'carts.user_id',
                'carts.session_id',
                'carts.product_id',
                'carts.quantity',
                DB::raw('COALESCE((SELECT image_url FROM images WHERE images.product_id = carts.product_id ORDER BY id DESC LIMIT 1), "") AS image_url')
            )
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.user_id', $user_id)
            ->get();
    }


    public function findItemById($productId, $user_Id)
    {
        return Cart::where('product_id', $productId)
            ->where('user_id', $user_Id)
            ->first();
    }

    public function deleteByProductId($product_id, $user_id)
    {
        $cartItem = self::where('product_id', $product_id)->where('user_id', $user_id)->first();
        if ($cartItem) {
            $cartItem->delete();
            return true;
        }
        return false;
    }
}
