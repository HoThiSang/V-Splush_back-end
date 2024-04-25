<?php

use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminCategoryController;
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


Route::get('/categories', [AdminCategoryController::class, 'index'])->name('get-all-category');
Route::get('/categories/{id}', [AdminCategoryController::class, 'edit'])->name('get-all-category-id');
Route::post('/categories-create', [AdminCategoryController::class, 'store'])->name('create-category');
Route::put('/categories-update/{id}', [AdminCategoryController::class, 'update'])->name('update-category');

// Route::resource('/categories', [AdminCategoryController::class, 'index'])->name('get-all-category');
// Comments
Route::get('/show-all-comments',[AdminCommentController::class,'index']);
Route::post('/add-comment',[AdminCommentController::class,'store']);
Route::put('update-comment/{id}',[AdminCommentController::class,'update']);
Route::delete('delete-comment/{id}',[AdminCommentController::class,'destroy']);
Route::get('/show-detail-comment/{id}',[AdminCommentController::class,'show']);