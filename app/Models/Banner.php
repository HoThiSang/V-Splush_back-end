<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $banners = DB::table($this->table)
            // ->whereNull('deleted_at')
            // ->orWhere('deleted_at', '>', now())
            ->get();
        return $banners;
    }


    public function deleteBannerById($id)
    {
        $banner = $this->findOrFail($id);
        $banner->delete();
        return $banner;
    }


    public function creatNewBanner($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }
    public function updateBanner($id, $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }
    public function getBannerById($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }
}
