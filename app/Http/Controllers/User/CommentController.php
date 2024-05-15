<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $comments;
    public function __construct()
    {
        $this->comments = new Comment();
    }
    public function index()
    {
        //
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
     *     path="/api/add-comment",
     *     summary="Create a new comment",
     *     tags={"Comment"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Comment data",
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="New Comment")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    
    public function store(CommentRequest $request)
    {
        //
        if ($request->isMethod('post')) {
            $data = [
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
                'content' => $request->content,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $comment = $this->comments->createNewComment($data);
            if ($comment) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Add new comment successfully',
                    'data' => $comment, 200
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed add new comment',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not post',
            ], 500);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}