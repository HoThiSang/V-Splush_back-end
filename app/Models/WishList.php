<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;


class WishList extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'wish_lists';

    protected $fillable = [
        'user_id',
        'product_id',
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }
  
    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }

    public function getAllWishList()
    {
        try {
            return $this->whereNull('deleted_at')
                ->orWhere('deleted_at', '>', now())
                ->get();
        } catch (\Exception $e) {
            Log::error('Error retrieving wishlists: ' . $e->getMessage());
            return [];
        }
    }

    public function deleteWishList($id)
    {
        $wishList = $this->findOrFail($id);
        return $wishList->delete();
    }
    public function createWishList($userId, $productId)
    {
        try {
            if (empty($userId) || empty($productId)) {
                throw new \Exception('User ID and Product ID are required');
            }
            $success = DB::table($this->table)->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'deleted_at' => null,
            ]);
            return $success;
        } catch (\Exception $e) {
            Log::error('Error creating wishlist: ' . $e->getMessage());
            return false;
        }
    }

}
