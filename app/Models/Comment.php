<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = ['content'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }

    public function getAllComments(){
        $comments=DB::table($this->table)
        ->get();
        return $comments;
    }

    public function getCommentById($id){
        $comment=DB::table($this->table)
        ->where('id',$id)
        ->first();
        return $comment;
    }

    public function updateComment($id,$data){
        return DB::table($this->table)
        ->where('id',$id)
        ->update($data);
    }

    public function deleteCommentById($id){
        return DB::table($this->table)
        ->where('id',$id)
        ->delete();
    }

    public function createNewComment($data){
        return DB::table($this->table)
        ->insert($data);
    }
}