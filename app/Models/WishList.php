<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
