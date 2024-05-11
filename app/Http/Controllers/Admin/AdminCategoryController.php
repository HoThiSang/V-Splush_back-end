<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;


/**
 * @OA\Info(
 *    title="Swagger with Laravel",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )

 */
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

 /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Category"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
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
     /**
     * @OA\Post(
     *     path="/api/categories-create",
     *     summary="Create a new category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data",
     *         @OA\JsonContent(
     *             required={"category_name"},
     *             @OA\Property(property="category_name", type="string", example="New Category")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
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
        if (!empty($id)) {
            $categoryDetail = $this->categories->getCategoryById($id);
            if (!empty($categoryDetail)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category retrieved successfully',
                    'data' => $categoryDetail
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to retrieve category',
                ], 500);
            }
        }

    }

    /**
     * Update the specified resource in storage.
     */
      /**
     * @OA\Put(
     *     path="/api/categories-update/{id}",
     *     summary="Update a category by ID",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data",
     *         @OA\JsonContent(
     *             required={"category_name"},
     *             @OA\Property(property="category_name", type="string", example="Updated Category")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Category not found"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    public function update(CategoryRequest $request, string $id)
    {
        if ($request->isMethod('put')) {
            $categoryDetail = $this->categories->getCategoryById($id);
            if (!empty($categoryDetail)) {
                $categoryData = [
                    'category_name' => $request->category_name,
                    'updated_at' => now()
                ];
                $categoryUpdated = $this->categories->updateCategory($id, $categoryData);
                if ($categoryUpdated) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Update category successfully',
                        'data' => $categoryUpdated
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Update category failed',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found',
                ], 404);
            }
        }
       
        return response()->json([
            'status' => 'error',
            'message' => 'The method not update',
        ], 500);

    }

    /**
     * Remove the specified resource from storage.
     */
     /**
     * @OA\Delete(
     *     path="/api/categories-delete/{id}",
     *     summary="Delete a category by ID",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *    @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Category not found")
     * )
     */
    public function destroy(string $id)
    {
        if (!empty($id)) {
            $product = $this->categories->deleteCategoryById($id);
            if ($product) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Deleted category successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found',
                ], 404);
            }
        }
    }

  /**
     * @OA\Get(
     *     path="/api/test",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function test()
    {
        $data = 'We are CodeQeens team';
        return response()->json($data);
    }
}
