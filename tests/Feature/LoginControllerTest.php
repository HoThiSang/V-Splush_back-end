<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase; // Import RefreshDatabase trait

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; // Use RefreshDatabase trait to handle database transactions

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
        $user = Mockery::mock(User::class);
        $user->shouldReceive('createToken')->andReturn((object)['plainTextToken' => 'dummy_token']);

        // Mock the static where method
        $userMock = Mockery::mock('overload:App\Models\User');
        $userMock->shouldReceive('where->first')->once()->andReturnNull();
        $userMock->shouldReceive('create')->once()->andReturn($user);

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

    public function testRegisterEmailExists()
    {
        // Mock the request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->once()->andReturn(true);
        $request->email = 'test@example.com';

        // Mock the User model
        $existingUser = Mockery::mock(User::class);

        // Mock the static where method
        $userMock = Mockery::mock('overload:App\Models\User');
        $userMock->shouldReceive('where->first')->once()->andReturn($existingUser);

        // Call the register function
        $controller = new AuthController();
        $response = $controller->register($request);

        // Assert the response
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Email already exists', $response->original['message']);
        $this->assertEquals('failed', $response->original['status']);
    }
}
