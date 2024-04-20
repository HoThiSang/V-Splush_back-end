<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';

    protected $fillable = [
        'image_name', 'image_url', 'publicId','deleted_at'
    ];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }
}
