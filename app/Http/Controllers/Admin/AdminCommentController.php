<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

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
}
