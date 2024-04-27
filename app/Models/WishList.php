<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WishList extends Model
{
    use HasFactory;
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

}
