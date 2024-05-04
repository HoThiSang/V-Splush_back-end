<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';

    protected $fillable = [
        'title',

    ];

    public function getAllPost()
    {
        $posts  = DB::table($this->table)->get();
        return $posts;
    }

    public function createNewPost($data)
    {
        $postId = DB::table($this->table)->insertGetId($data);
        if ($postId) {
            $post = DB::table($this->table)->find($postId);
            return $post;
        }
        return null;
    }

    public function getPostById($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }

    public function deletePostById($id)
    {
        $deletedPost = DB::table($this->table)->where('id', $id)->first();
        DB::table($this->table)->where('id', $id)->delete();
        return $deletedPost;
    }

    public function updatePost($id, $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }
}
