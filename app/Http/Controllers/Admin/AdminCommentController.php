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
     */


    protected $comments;
    public function __construct()
    {
        $this->comments = new Comment();
    }

    /**
     * @OA\Get(
     *     path="/api/show-all-comments",
     *     summary="Get all comments",
     *     tags={"Comment"},
     *     @OA\Response(response="200", description="Success"),
     *
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/delete-comment/{id}",
     *     summary="Delete a comment by ID",
     *     tags={"Comment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to delete",
     *    @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Comment not found")
     * )
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

    /**
     * Display the specified resource.
     */

        /**
     * @OA\Get(
     *     path="/api/show-detail-comment/{id}",
     *     summary="Detail a comment by ID",
     *     tags={"Comment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to detail",
     *    @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Comment not found")
     * )
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
                    'message' => 'This comment does not exist',
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

      /**
     * @OA\Put(
     *     path="/api/update-comment/{id}",
     *     summary="Update a comment by ID",
     *     tags={"Comment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to update",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Comment data",
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Updated Comment")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Comment not found"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
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
