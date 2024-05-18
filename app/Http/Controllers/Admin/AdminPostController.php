<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Slide;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;

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
    /**
     * @OA\Get(
     *     path="/api/admin-show-all-post",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
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

    /**
     * @OA\Post(
     *     path="/api/admin-create-post",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title of post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Content of post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image_url",
     *                     type="string",
     *                     format="binary",
     *                     description="Image of post"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create new post successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function store(PostRequest $request)
    {
        if (Auth()->check()) {
            $user_id = Auth()->user()->id;
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
                    'image_name' => $request->title,
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
}
    /**
     * Display the specified resource.
     */
     /**
     * @OA\Get(
     *     path="/api/admin-show-post/{id}",
     *     summary="Show a post by ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to show",
     *    @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */
    public function show(string $id)
    {
        $post = $this->posts->getPostById($id);
        if (!empty($post)) {

            return response()->json([
                'status' => 'success',
                'message' => 'Get one post successfully!',
                'data' => $post
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found post with id !' . $id,
            ], 404);
        }
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
    /**
     * @OA\Post(
     *     path="/api/admin-update-post/{id}",
     *     summary="Update a category by ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to update",
     *         @OA\Schema(
     *             type="integer",
     *             format="uuid"
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title of post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Content of post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image_url",
     *                     type="string",
     *                     format="binary",
     *                     description="Image of post"
     *                 )
     *             )
     *         )
     *     ),
     *   
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Category not found"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    public function update(PostRequest $request, string $id)
    {
        if ($request->method() === 'POST') {
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
    /**
     * @OA\Delete(
     *     path="/api/admin-delete-post/{id}",
     *     summary="Delete a post by ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to delete",
     *    @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */
    public function destroy(string $id)
    {
        $post = $this->posts->getPostById($id);
        if (!empty($post)) {
            $post = $this->posts->deletePostById($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Delete post successfully!',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found post with id !' . $id,
            ], 404);
        }
    }
}
