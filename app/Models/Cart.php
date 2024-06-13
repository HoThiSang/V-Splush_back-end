<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'products.discount',
            'carts.user_id',
            'carts.session_id',
            'carts.product_id',
            'carts.unit_price',
            'carts.quantity',
            'carts.created_at',
            'carts.updated_at',
            DB::raw('COALESCE((SELECT image_url FROM images WHERE images.product_id = carts.product_id ORDER BY id DESC LIMIT 1), "") AS image_url')
        )
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->where('carts.user_id', $user_id)
        ->get();
}


    // public function findItemById($productId, $user_Id)
    // {
    //     return Cart::where('product_id', $productId)
    //         ->where('user_id', $user_Id)
    //         ->first();
    // }
    public function findItemById($productId, $user_Id)
    {
        // Ghi nhật ký các giá trị đầu vào
        Log::debug('findItemById called with: ', ['productId' => $productId, 'user_Id' => $user_Id]);

        $cartItem = Cart::where('product_id', $productId)
            ->where('user_id', $user_Id)
            ->first();

        // Ghi nhật ký truy vấn SQL
        Log::debug('Executed query: ', [
            'query' => Cart::where('product_id', $productId)
                ->where('user_id', $user_Id)
                ->toSql()
        ]);

        // Ghi nhật ký kết quả truy vấn
        if ($cartItem) {
            Log::debug('Cart item found: ', ['cartItem' => $cartItem]);
        } else {
            Log::debug('No cart item found for product_id: ' . $productId . ' and user_id: ' . $user_Id);
        }

        return $cartItem;
    }


    // public function deleteByProductId($product_id, $user_id)
    // {
    //     $cartItem = self::where('product_id', $product_id)->where('user_id', $user_id)->first();
    //     if ($cartItem) {
    //         $cartItem->delete();
    //         return true;
    //     }
    //     return false;
    // }
    public function deleteByProductId($product_id, $user_id)
    {
        $cartItem = Cart::where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->first();
        if ($cartItem) {
            $cartItem->delete();
            return $cartItem;
        }
        return $cartItem;
    }
}
