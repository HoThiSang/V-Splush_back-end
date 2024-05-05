<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class AdminCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *  public function index(){

     */
    protected $comments;
    public function __construct()
    {
        $this->comments = new Comment();
    }
    public function index(Request $request)
    {
        //
        if ($request->isMethod('get')) {
            $comments = $this->comments->getAllComments();
            if ($comments) {
                return response()->json([
                    'satus' => 'success',
                    'message' => 'Show all comments sucessfully',
                    'data' => $comments
                ], 200);
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Failed show all comment',
                    ],
                    404
                );
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not get',
            ], 405);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        //
        if ($request->isMethod('delete')) {
            if (!empty($id)) {
                $comment = $this->comments->deleteCommentById($id);
                if ($comment) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Delete comment successfully',
                        'data' => $comment, 200
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed delete comment',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This comment does not exist',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not delete',
            ], 500);
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
    public function show(Request $request, string $id)
    {
        //
        if ($request->isMethod('get')) {
            if (!empty($id)) {
                $comment = $this->comments->getCommentById($id);
                if ($comment) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Show detail comment successfully',
                        'data' => $comment, 200
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Show detail comment',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Show detail comment',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not get',
            ], 500);
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
    public function update(CommentRequest $request, string $id)
    {
        //
        if ($request->isMethod('put')) {
            $dataUpdate = [
                'content' => $request->content,
                'updated_at' => now()
            ];

            $comment = $this->comments->updateComment($id, $dataUpdate);
            if ($comment) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Update comment successfully',
                    'data' => $comment, 200
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed update comment',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not put',
            ], 500);
        }
    }
}