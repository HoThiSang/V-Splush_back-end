<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public function getAllImageByProductId($product_id)
    {

        $images = DB::table($this->table)->where('product_id', $product_id)->get();
        return $images;
    }
    public function createImageByProductId($data)
    {
        $image = DB::table($this->table)->insertGetId($data);
        return $image;
    }
    public function updateImage($product_id, $data)
    {
        return DB::table($this->table)
            ->where('product_id', $product_id)
            ->update($data);
    }
    public static function deleteImagesByProductId($productId)
    {
        self::where('product_id', $productId)->delete();
    }
}
