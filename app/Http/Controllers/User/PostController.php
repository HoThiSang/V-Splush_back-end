<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    protected $post;

    public function __construct()
    {
        $this->post = new Post();
    }

    public function index()
    {
        $posts = $this->post->getAllPosts();
        if ($posts) {
            return response()->json([
                'status' => 'success',
                'message' => 'Get 3 post successfully',
                'data' => $posts
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
    }
}
