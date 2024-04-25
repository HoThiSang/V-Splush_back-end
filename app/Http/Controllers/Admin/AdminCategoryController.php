<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    protected $categories;

    public function __construct()
    {
        $this->categories = new Category();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allCategories = $this->categories->getAllCategories();

        if (!empty($allCategories)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => $allCategories
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve categories',
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
    public function store(CategoryRequest $request)
    {
        if ($request->isMethod('post')) {
            $categoryData = [
                'category_name' => $request->category_name,
                'created_at' => now()
            ];
            $category = $this->categories->createNewCategory($categoryData);
            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Add new category successfully',
                    'data' => $category
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Add new category field',
                ], 500);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'The method not post',
        ], 500);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
    public function update(CategoryRequest $request, string $id)
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
