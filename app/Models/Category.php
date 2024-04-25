<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';

    protected $fillable = ['category_name', 'deleted_at'];

    public function products(){
        return $this->hasMany('App\Models\Product', 'category_id','id');
    }


    public function getAllCategories()
    {
        $categories  = DB::table($this->table)->whereNull('deleted_at')
            ->orWhere('deleted_at', '>', now())->get();

        return $categories;
    }

    public function getCategoryById($id)
    {
        $categories  = DB::table($this->table)->where('id', $id)->first();
        return $categories;
    }

    public function createNewCategory($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateCategory($id, $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }

}
