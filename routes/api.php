<?php

use App\Http\Controllers\Admin\AdminCommentController;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/show-all-comments',[AdminCommentController::class,'index']);
Route::post('/add-comment',[AdminCommentController::class,'store']);
Route::put('update-comment/{id}',[AdminCommentController::class,'update']);
Route::delete('delete-comment/{id}',[AdminCommentController::class,'destroy']);
Route::get('/show-detail-comment/{id}',[AdminCommentController::class,'show']);