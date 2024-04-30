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
        $this->comments=new Comment();
     }
    public function index(Request $request)
    {
        //
        if ($request->isMethod('get')) {
        $comments=$this->comments->getAllComments();
        if($comments){
            return response()->json([
                'satus'=>'success',
                'message'=>'Show all comments sucessfully',
                'data'=>$comments],200);
        }
        else{
            return response()->json([
                'status'=>'error',
                'message'=>'Failed show all comment',],404
            );
        }
    }
    return response()->json([
        'status' => 'error',
        'message' => 'The method not get',
    ], 405);
    }
      /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        //
        if ($request->isMethod('delete')) {
        $comment=$this->comments->deleteCommentById($id);
        if($comment){
            return response()->json([
                'status' =>'success',
                'message'=>'Delete comment successfully',
                'data'=>$comment,200
            ]);
        }
    else{
        return response()->json([
            'status' => 'error',
            'message' => 'Failed delete comment',
        ], 500);
    }
    return response()->json([
        'status' => 'error',
        'message' => 'not delete comment',
    ], 500);
}
return response()->json([
    'status' => 'error',
    'message' => 'The method not delete',
], 500);
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
        $data=[
            'product_id' =>$request->product_id,
            'user_id' => $request->user_id,
            'content' =>$request->content,
            'created_at'=>now(),
            'updated_at'=>now()
        ];
        $comment=$this->comments->createNewComment($data);
        if($comment){
            return response()->json([
                'status' =>'success',
                'message'=>'Add new comment successfully',
                'data'=>$comment,200
            ]);
        }
    else{
        return response()->json([
            'status' => 'error',
            'message' => 'Add new comment',
        ], 500);
    }
    return response()->json([
        'status' => 'error',
        'message' => 'Add new comment',
    ], 500);
}
return response()->json([
    'status' => 'error',
    'message' => 'The method not post',
], 500);
}

}
