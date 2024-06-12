<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banners';

    protected $fillable = [
        'title',
        'sub_title',
        'content',
        'image_url',
        'image_name'
    ];

    public function getAllBanner()
    {
        return $this->whereNull('deleted_at')->orWhere('deleted_at', '>', now())->get();
    }


    public function deleteBannerById($id)
    {
        return $this->findOrFail($id)->delete();
    }


    public function creatNewBanner($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function updateBanner($id, $data)
    {
        $banner = $this->findOrFail($id);
        $banner->fill($data);
        $banner->save();
        return $banner;
    }
    public function getBannerById($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }
}
