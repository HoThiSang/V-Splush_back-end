<?php
// app/Http/Controllers/Admin/AdminCategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class AdminCategoryControllerMock extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $categoryData = $request->only('category_name');
        $createdCategory = $this->categoryService->createCategory($categoryData);

        return response()->json([
            'status' => 'success',
            'message' => 'Add new category successfully',
            'data' => $createdCategory
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $updatedCategoryData = $request->only('category_name');
        $updatedCategory = $this->categoryService->updateCategory($id, $updatedCategoryData);

        return response()->json([
            'status' => 'success',
            'message' => 'Update category successfully',
            'data' => $updatedCategory,
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->categoryService->deleteCategory($id);
        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted category successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category retrieved successfully',
                'data' => $category
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
