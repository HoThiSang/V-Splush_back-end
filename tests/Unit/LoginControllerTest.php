<?php

namespace Tests\Unit;

use App\Http\Controllers\User\UserController;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure the UserFactory is correctly loaded
        \Illuminate\Database\Eloquent\Factories\Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    public function testSuccessfulLogin()
    {
        // Create a user using the factory
        $user = Users::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        // Create a request
        $request = Request::create('/api/user/login', 'POST', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // Instantiate controller and call login method
        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(200, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('User Login Success', $responseData['message']);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals($user->id, $responseData['user']['id']);
        $this->assertEquals($user->email, $responseData['user']['email']);
    }

    public function testEmptyEmailAndPassword()
    {
        $request = Request::create('/api/user/login', 'POST', [
            'email' => '',
            'password' => '',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(422, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('The email field is required.', $responseData['errors'][0]);
        $this->assertEquals('The password field is required.', $responseData['errors'][1]);
    }

    public function testCorrectEmailWrongPassword()
    {
        $user = Users::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $request = Request::create('/api/user/login', 'POST', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(401, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('Incorrect password', $responseData['message']);
    }

    public function testWrongEmailCorrectPassword()
    {
        $request = Request::create('/api/user/login', 'POST', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(404, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('Email does not exist', $responseData['message']);
    }

    public function testWrongEmailAndPassword()
    {
        $request = Request::create('/api/user/login', 'POST', [
            'email' => 'wrong@example.com',
            'password' => 'wrong-password',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(404, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('Email does not exist', $responseData['message']);
    }

    public function testAdminLoginSuccess()
    {
        $user = Users::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        $request = Request::create('/api/user/login', 'POST', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(200, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Admin Login Success', $responseData['message']);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals($user->id, $responseData['user']['id']);
        $this->assertEquals($user->email, $responseData['user']['email']);
    }

    public function testUnauthorizedRole()
    {
        $user = Users::factory()->create([
            'email' => 'unauthorized@example.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);

        $request = Request::create('/api/user/login', 'POST', [
            'email' => 'unauthorized@example.com',
            'password' => 'password',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(403, $response->status());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('You are not authorized to access this resource', $responseData['message']);
    }
}
