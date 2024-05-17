<?php

use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Mail\UserSendMailController;
use App\Http\Controllers\Admin\AdminWishListControllor;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CartController;

use App\Http\Controllers\User\CommentController;

use App\Http\Controllers\User\WishListController;



use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\UserCartController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Models\Role;
use Cloudinary\Api\Admin\AdminApi;

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
Route::delete('/delete-wish-list/{id}', [AdminWishListControllor::class, 'destroy'])->name('delete-wish-list');
Route::post('/create-wishlist', [WishListController::class, 'store'])->name('create-wishlist');


// Route::resource('/categories', [AdminCategoryController::class, 'index'])->name('get-all-category');
// Comments
Route::get('/show-all-comments', [AdminCommentController::class, 'index']);
Route::post('/add-comment', [CommentController::class, 'store']);
Route::put('update-comment/{id}', [AdminCommentController::class, 'update']);
Route::delete('delete-comment/{id}', [AdminCommentController::class, 'destroy']);
Route::get('/show-detail-comment/{id}', [AdminCommentController::class, 'show']);
//Route Product
Route::get('/admin-product',[AdminProductController::class,'index'])->name('get-all-product');
Route::post('/admin-add-product',[AdminProductController::class,'store'])->name('create-product');
Route::get('/admin-product-detail/{id}', [AdminProductController::class, 'show'])->name('product-detail');
Route::post('/admin-product-update/{id}', [AdminProductController::class, 'update'])->name('admin-product-update');Route::delete('/admin-product-delete/{id}', [AdminProductController::class, 'destroy'])->name('admin-product-delete');// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route user
Route::get('admin-user', [AdminUserController::class, 'index'])->name('admin-user');
Route::post('update-user/{id}', [AdminUserController::class, 'update'])->name('update-user');

Route::get('admin-show-all-post', [AdminPostController::class, 'index'])->name('admin-show-all-post');
Route::post('/admin-create-post', [AdminPostController::class, 'store'])->name('admin-create-post');
Route::delete('/admin-delete-post/{id}', [AdminPostController::class, 'destroy'])->name('admin-delete-post');
Route::get('/admin-show-post/{id}', [AdminPostController::class, 'show'])->name('admin-show-post');
Route::post('/admin-update-post/{id}', [AdminPostController::class, 'update'])->name('admin-update-post');
Route::put('/admin-update-post/{id}', [AdminPostController::class, 'update'])->name('admin-update-post');
Route::get('admin-show-all-banner', [AdminBannerController::class, 'index'])->name('admin-show-all-banner');
Route::delete('/admin-delete-banner/{id}', [AdminBannerController::class, 'destroy'])->name('admin-delete-banner');

Route::post('/admin-create-banner', [AdminBannerController::class, 'store'])->name('admin-create-banner');
Route::post('/admin-update-banner/{id}', [AdminBannerController::class, 'update'])->name('admin-update-banner');
Route::get('/test', [AdminCategoryController::class, 'test']);

Route::get('admin-show-all-post', [AdminPostController::class, 'index'])->name('admin-show-all-post');

Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group( function () {
    Route::post('logout', [UserController::class, 'logout'])->name('logout');
});
Route::get('/admin-contact', [AdminContactController::class, 'index'])->name('admin-contact');
Route::get('/admin-view-contact/{id}', [AdminContactController::class, 'show'])->name('admin-view-contact');
Route::post('user-send-contact', [UserSendMailController::class, 'sendEmail'])->name('user-create-contact');
Route::post('/admin-reply-contact/{id}', [UserSendMailController::class, 'replyEmail'])->name('admin-reply-contact');
Route::delete('/delete-contact/{id}', [AdminContactController::class, 'destroy']);
// Order
Route::get('/admin-show-all-orders',[AdminOrderController::class,'index'])->name('admin-show-all-order');
Route::post('/admin-update-status-order/{id}',[AdminOrderController::class,'update'])->name('admin-update-status-order');
Route::get('/admin-show-detail-order/{id}',[AdminOrderController::class,'edit'])->name('admin-show-detail-order');
//Cart
Route::get('/shopping-cart', [CartController::class, 'showCart'])->name('showtocart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('add-to-cart');
Route::post('/update-cart/{id}', [CartController::class, 'updateCart'])->name('updateCart');
Route::delete('/delete-cart/{id}', [CartController::class, 'deleteCart'])->name('delete-cart');
Route::get('/search-product/{keyword}', [ProductController::class, 'search'])->name('search-product');
