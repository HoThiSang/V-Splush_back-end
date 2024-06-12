<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $orderCount = Order::count();
        $productCount = Product::count();
        $postCount = Post::count();
        $categoryCount = Category::count();
        $contactCount = Contact::count();
        $userCount = User::count();

        return response()->json([
            'status' => 'success',
            'orderCount' => $orderCount,
            'productCount' => $productCount,
            'postCount' => $postCount,
            'categoryCount' => $categoryCount,
            'contactCount' => $contactCount,
            'userCount' => $userCount
        ]);
    }
}
