<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use App\Models\Mocks\UserMock;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\AuthController;

class AuthControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testRegisterSuccess()
    {
        // Mock the request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->once()->andReturn(true);
        $request->name = 'Test User';
        $request->email = 'test@example.com';
        $request->phone = '1234567890';
        $request->address = '123 Test St';
        $request->password = 'password';
        $request->confirmPassword = 'password';

        // Mock the User model
        $user = Mockery::mock(UserMock::class);
        $user->shouldReceive('createToken')->andReturn((object)['plainTextToken' => 'dummy_token']);

        // Mock the static where method
        $userMock = Mockery::mock('overload:App\Models\Mocks\UserMock');
        $userMock->shouldReceive('where')->andReturnSelf(); // Mock chaining where
        $userMock->shouldReceive('first')->andReturnNull(); // Simulate no existing user
        $userMock->shouldReceive('create')->andReturn($user); // Mock user creation

        // Hash password
        Hash::shouldReceive('make')->once()->with('password')->andReturn('hashed_password');

        // Call the register function
        $controller = new AuthController();
        $response = $controller->register($request);

        // Assert the response
        $this->assertEquals(201, $response->status());
        $this->assertEquals('Registration Success', $response->original['message']);
        $this->assertEquals('success', $response->original['status']);
        $this->assertEquals('dummy_token', $response->original['token']);
    }
}
