<?php

namespace Tests\Feature;

use Mockery;
use App\Models\Category;
use App\Http\Controllers\Admin\AdminCategoryControllerMock;
use App\Services\CategoryService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class AdminCategoryControllerTest extends TestCase
{
    /**
     * Test case for testing category creation.
     *
     * @return void
     */
    // Adjust your test case to properly set up Mockery for CategoryService

    // public function testCreateCategory()
    // {
    //     $categoryData = [
    //         'category_name' => '', // Missing category_name to trigger validation error
    //     ];

    //     // Mock CategoryService
    //     $mockCategoryService = Mockery::mock(CategoryService::class);
    //     $mockCategoryService->shouldReceive('createCategory')
    //         ->never(); // We expect createCategory() not to be called due to validation failure

    //     // Inject mock service into controller
    //     $controller = new \App\Http\Controllers\Admin\AdminCategoryControllerMock($mockCategoryService);

    //     // Create a request with JSON body
    //     $request = new \Illuminate\Http\Request();
    //     $request->setJson($categoryData);

    //     $response = $controller->store($request);

    //     // Assert HTTP status code
    //     $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

    //     // Assert JSON response structure and content
    //     $response->assertJson([
    //         'status' => 'error',
    //         'message' => 'The given data was invalid.',
    //         'errors' => [
    //             'category_name' => ['The category name field is required.'],
    //         ],
    //         'data' => null,
    //     ]);
    // }






    // public function testUpdateCategory()
    // {
    //     $categoryId = 1;
    //     $updatedCategoryData = [
    //         'category_name' => '', // Missing category_name to trigger validation error
    //     ];

    //     // Mock CategoryService
    //     $mockCategoryService = Mockery::mock(CategoryService::class);
    //     $mockCategoryService->shouldReceive('updateCategory')
    //         ->never(); // We expect updateCategory() not to be called due to validation failure

    //     // Inject mock service into controller
    //     $controller = new \App\Http\Controllers\Admin\AdminCategoryControllerMock($mockCategoryService);

    //     // Create a request with JSON body
    //     $request = new \Illuminate\Http\Request();
    //     $request->setJson($updatedCategoryData);

    //     $response = $controller->update($request, $categoryId);

    //     // Assert HTTP status code
    //     $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

    //     // Assert JSON response structure and content
    //     $response->assertJson([
    //         'status' => 'error',
    //         'message' => 'The given data was invalid.',
    //         'errors' => [
    //             'category_name' => ['The category name field is required.'],
    //         ],
    //         'data' => null,
    //     ]);
    // }







    /**
     * Test case for testing category deletion.
     *
     * @return void
     */
    public function testDeleteCategory()
    {
        $categoryId = 1;

        // Mock CategoryService
        $mockCategoryService = Mockery::mock(CategoryService::class);
        $mockCategoryService->shouldReceive('deleteCategory')
            ->once()
            ->with($categoryId)
            ->andReturn(true);

        // Inject mock service into controller
        $controller = new AdminCategoryControllerMock($mockCategoryService);

        $response = $controller->destroy($categoryId);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Deleted category successfully', $response->getData()->message);
    }

    /**
     * Test case for retrieving all categories.
     *
     * @return void
     */
    public function testGetAllCategories()
    {
        // Mock Category model
        $mockCategoryModel = Mockery::mock(Category::class);

        // Mock data for categories
        $categoryData = [
            new Category(['id' => 1, 'category_name' => 'Category 1']),
            new Category(['id' => 2, 'category_name' => 'Category 2']),
            new Category(['id' => 3, 'category_name' => 'Category 3']),
        ];

        // Expect the 'all' method to be called once and return the mock data
        $mockCategoryModel->shouldReceive('all')
            ->once()
            ->andReturn(new Collection($categoryData));

        // Create an instance of CategoryService with the mocked Category model
        $categoryService = new CategoryService($mockCategoryModel);

        // Call getAllCategories() method
        $result = $categoryService->getAllCategories();

        // Assertions
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(count($categoryData), $result->count());

        // Assert individual category attributes
        foreach ($categoryData as $key => $category) {
            $this->assertEquals($category->id, $result[$key]->id);
            $this->assertEquals($category->category_name, $result[$key]->category_name);
        }
    }



    /**
     * Test case for retrieving a single category by ID.
     *
     * @return void
     */
    public function testGetCategoryById()
    {
        $categoryId = 1;
        $categoryData = ['category_name' => 'Test Category']; // Adjusted to match the expected structure

        // Mock CategoryService
        $mockCategoryService = Mockery::mock(CategoryService::class);
        $mockCategoryService->shouldReceive('getCategoryById')
            ->once()
            ->with($categoryId)
            ->andReturn(new Category(['id' => $categoryId, 'category_name' => $categoryData['category_name']])); // Ensure the mock returns both id and category_name

        // Inject mock service into controller
        $controller = new AdminCategoryControllerMock($mockCategoryService);

        $response = $controller->show($categoryId);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Category retrieved successfully', $response->getData()->message);

        // Compare only the 'category_name'
        $this->assertEquals($categoryData['category_name'], $response->getData()->data->category_name);
    }


    /**
     * Test case for handling invalid category ID in show endpoint.
     *
     * @return void
     */
    public function testInvalidCategoryIdInShow()
    {
        $categoryId = 999;

        // Mock CategoryService
        $mockCategoryService = Mockery::mock(CategoryService::class);
        $mockCategoryService->shouldReceive('getCategoryById')
            ->once()
            ->with($categoryId)
            ->andReturn(null); // Simulate not found scenario

        // Inject mock service into controller
        $controller = new AdminCategoryControllerMock($mockCategoryService);

        $response = $controller->show($categoryId);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Category not found', $response->getData()->message);
    }

    /**
     * Helper function to create a request with JSON body.
     *
     * @param array $data
     * @return \Illuminate\Http\Request
     */
    protected function createRequestWithJsonBody(array $data)
    {
        $request = new \Illuminate\Http\Request();
        $request->setJson($data);
        return $request;
    }

    /**
     * Clean up Mockery resources after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
