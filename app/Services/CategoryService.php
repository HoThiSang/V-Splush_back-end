<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    protected $categoryModel;

    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function getAllCategories()
    {
        return $this->categoryModel->all();
    }

    public function createCategory($categoryData)
    {
        return $this->categoryModel->create($categoryData);
    }

    public function updateCategory($id, $categoryData)
    {
        $category = $this->categoryModel->findOrFail($id);
        $category->update($categoryData);
        return $category;
    }

    public function deleteCategory($categoryId)
    {
        $category = $this->categoryModel->findOrFail($categoryId);
        $category->delete();
        return true; // Return true for successful deletion
    }

    public function getCategoryById($categoryId)
    {
        return $this->categoryModel->find($categoryId);
    }
}
