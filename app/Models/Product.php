<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
   
    public function images(){
        return $this->hasMany('App\Models\Image', 'product_id','id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id','id');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment', 'product_id','id');
    }

    public function wishLists(){
        return $this->hasMany('App\Models\Product', 'product_id','id');
    }
}
