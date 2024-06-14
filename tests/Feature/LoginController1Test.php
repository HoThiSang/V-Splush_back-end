<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController1Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testEmptyEmailAndPassword()
{
    // Tạo request với email và password rỗng
    $request = new Request([
        'email' => '',
        'password' => '',
    ]);

    // Gọi phương thức login từ UserController hoặc AuthController (tuỳ vào cách bạn thiết kế)
    $controller = new UserController();
    $response = $controller->login($request);

    // Kiểm tra kết quả trả về
    $this->assertEquals(422, $response->getStatusCode());
    $responseData = json_decode($response->getContent(), true);
    $this->assertEquals('failed', $responseData['status']);
    $this->assertEquals('The email field is required.', $responseData['errors'][0]);
    $this->assertEquals('The password field is required.', $responseData['errors'][1]);
}

// public function testSuccessfulLogin()
// {
//     // Tạo user trong database
//     $user = User::factory()->create([
//         'email' => 'user@example.com',
//         'password' => Hash::make('password'),
//     ]);

//     // Tạo request để gọi phương thức login
//     $request = new Request([
//         'email' => 'user@example.com',
//         'password' => 'password',
//     ]);

//     // Gọi phương thức login từ UserController hoặc AuthController (tuỳ vào cách bạn thiết kế)
//     $controller = new UserController();
//     $response = $controller->login($request);

//     // Kiểm tra kết quả trả về
//     $this->assertEquals(200, $response->getStatusCode());
//     $responseData = json_decode($response->getContent(), true);
//     $this->assertEquals('success', $responseData['status']);
//     $this->assertEquals('User Login Success', $responseData['message']);
//     $this->assertArrayHasKey('token', $responseData);
//     $this->assertEquals($user->id, $responseData['user']['id']);
//     $this->assertEquals($user->email, $responseData['user']['email']);
// }

public function testNonExistentEmail()
{
    // Tạo request với email không tồn tại trong database
    $request = new Request([
        'email' => 'nonexistent@example.com',
        'password' => 'password',
    ]);

    // Gọi phương thức login từ UserController hoặc AuthController (tuỳ vào cách bạn thiết kế)
    $controller = new UserController();
    $response = $controller->login($request);

    // Kiểm tra kết quả trả về
    $this->assertEquals(404, $response->getStatusCode());
    $responseData = json_decode($response->getContent(), true);
    $this->assertEquals('failed', $responseData['status']);
    $this->assertEquals('Email does not exist', $responseData['message']);
}



}
