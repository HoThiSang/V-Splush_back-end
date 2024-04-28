<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Slide;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminPostController extends Controller
{
    protected $posts;


    public function __construct()
    {
        $this->posts = new Post();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = $this->posts->getAllPost();
        if (!empty($posts)) {
            return response()->json(['status' => 'success', 'message' => 'Show all post successfully!', 'data' => $posts], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Not found any post '], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   
    public function store(PostRequest $request)
    {
        if ($request->isMethod('POST')) {
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'upload_image'
                ])->getSecurePath();
                $publicId = Cloudinary::getPublicId();
                $extension = $file->getClientOriginalName();
                $filename = time() . '_' . $extension;
                $postData = [
                    'title' => $request->title,
                    'content' => $request->content,
                    'image_name' => $request->image_name,
                    'image_url' => $uploadedFileUrl,
                    'publicId' => $publicId
                ];
            } else {
                $postData = [
                    'title' => $request->title,
                    'content' => $request->content,
                ];
            }
    
            $postInserted = $this->posts->createNewPost($postData);
    
            if ($postInserted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Add new post successfully',
                    'data' => $postInserted
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add new post',
                ], 422);
            }
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Method is not supported',
        ], 405);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        if ($request->method() === 'PUT') {
            $post = $this->posts->getPostById($id);
            if (!empty($post)) {
                $postData = [
                    'title' => $request->title,
                    'content' => $request->content,
                ];
    
                if ($request->hasFile('image_url')) {
                    $file = $request->file('image_url');
                    $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'upload_image'
                    ])->getSecurePath();
                    $publicId = Cloudinary::getPublicId();
                    
                    $postData['image_name'] = $request->title;
                    $postData['image_url'] = $uploadedFileUrl;
                    $postData['publicId'] = $publicId;
                }
    
                $postInserted = $this->posts->updatePost($id, $postData);
    
                if ($postInserted) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Update post successfully',
                        'data' => $postInserted
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update post',
                    ], 422);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request method',
            ], 405);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
