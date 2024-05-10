<?php

use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminWishListControllor;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\User\UserController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/categories', [AdminCategoryController::class, 'index'])->name('get-all-category');
Route::get('/categories/{id}', [AdminCategoryController::class, 'edit'])->name('get-all-category-id');
Route::post('/categories-create', [AdminCategoryController::class, 'store'])->name('create-category');
Route::put('/categories-update/{id}', [AdminCategoryController::class, 'update'])->name('update-category');

Route::delete('/categories-delete/{id}', [AdminCategoryController::class, 'destroy'])->name('update-category');
Route::get('/show-allwishlist', [AdminWishListControllor::class, 'index'])->name('show-allwishlist');
Route::delete('/delete-wish-list/{id}', [AdminWishListControllor::class, 'index'])->name('delete-wish-list');


// Route::resource('/categories', [AdminCategoryController::class, 'index'])->name('get-all-category');
// Comments
Route::get('/show-all-comments',[AdminCommentController::class,'index']);
Route::post('/add-comment',[AdminCommentController::class,'store']);
Route::put('update-comment/{id}',[AdminCommentController::class,'update']);
Route::delete('delete-comment/{id}',[AdminCommentController::class,'destroy']);
Route::get('/show-detail-comment/{id}',[AdminCommentController::class,'show']);

Route::get('/admin-product',[AdminProductController::class,'index'])->name('get-all-product');
Route::post('/admin-add-product',[AdminProductController::class,'store'])->name('create-product');
Route::delete('/admin-product-delete/{id}',[AdminProductController::class,'destroy'])->name('delete-product');

Route::get('admin-user', [AdminUserController::class, 'index'])->name('admin-user');

Route::get('admin-show-all-post', [AdminPostController::class, 'index'])->name('admin-show-all-post');
Route::post('/admin-create-post', [AdminPostController::class, 'store'])->name('admin-create-post');
Route::delete('/admin-delete-post/{id}', [AdminPostController::class, 'destroy'])->name('admin-delete-post');
Route::get('/admin-show-post/{id}', [AdminPostController::class, 'show'])->name('admin-show-post');
Route::put('/admin-update-post/{id}', [AdminPostController::class, 'update'])->name('admin-update-post');

Route::get('admin-show-all-banner', [AdminBannerController::class, 'index'])->name('admin-show-all-banner');
Route::delete('/admin-delete-banner/{id}', [AdminBannerController::class, 'destroy'])->name('admin-delete-banner');

Route::get('/test', [AdminCategoryController::class, 'test']);

Route::get('admin-show-all-post', [AdminPostController::class, 'index'])->name('admin-show-all-post');

Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');