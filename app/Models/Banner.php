<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Banner extends Model
{
    use HasFactory;
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
        ->whereNull('deleted_at')
        ->orWhere('deleted_at', '>', now())
        ->get();
        return $banners;
    }
    
}
