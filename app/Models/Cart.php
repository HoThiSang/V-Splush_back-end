<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price'
    ];
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }
    public function getAllCart($user_id)
    {
        $cart = DB::table('carts')->where('user_id', $user_id)->get();
        return $cart;
    }
    public function getAllCarts($user_id)
    {
        return DB::table('carts')
            ->select('carts.id', 'products.product_name', 'carts.total_price', 'products.discount', 'carts.user_id', 'carts.session_id', 'carts.product_id', 'carts.unit_price', 'carts.quantity', 'carts.created_at', 'carts.updated_at', DB::raw('MAX(images.image_url) as image_url'))
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->where('carts.user_id', $user_id)
            ->groupBy('carts.id', 'products.product_name', 'carts.total_price', 'products.discount', 'carts.user_id', 'carts.session_id', 'carts.product_id', 'carts.unit_price', 'carts.quantity', 'carts.created_at', 'carts.updated_at')
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
            DB::table('images')->where('product_id', $product_id)->delete();
            return true;
        }
        return false;
    }


}