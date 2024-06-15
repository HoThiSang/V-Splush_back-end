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
     * Test case for testing category deletion.
     *
     * @return void
     */
    public function testDeleteCategory()
    {
        $categoryId = 1;

        $mockCategoryService = Mockery::mock(CategoryService::class);
        $mockCategoryService->shouldReceive('deleteCategory')
            ->once()
            ->with($categoryId)
            ->andReturn(true);

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
        $mockCategoryModel = Mockery::mock(Category::class);

        $categoryData = [
            new Category(['id' => 1, 'category_name' => 'Category 1']),
            new Category(['id' => 2, 'category_name' => 'Category 2']),
            new Category(['id' => 3, 'category_name' => 'Category 3']),
        ];

        $mockCategoryModel->shouldReceive('all')
            ->once()
            ->andReturn(new Collection($categoryData));

        $categoryService = new CategoryService($mockCategoryModel);

        $result = $categoryService->getAllCategories();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(count($categoryData), $result->count());

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
        $categoryData = ['category_name' => 'Test Category'];

        $mockCategoryService = Mockery::mock(CategoryService::class);
        $mockCategoryService->shouldReceive('getCategoryById')
            ->once()
            ->with($categoryId)
            ->andReturn(new Category(['id' => $categoryId, 'category_name' => $categoryData['category_name']])); // Ensure the mock returns both id and category_name

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
            ->andReturn(null);

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
