<?php

namespace Tests\Unit;

use App\Http\Controllers\User\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;


    public function testSuccessfulLogin()
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->email = 'thisang@gmail.com';
        $user->role_id = 1;
        $user->password = Hash::make('correctpassword');

        $request = Request::create('/api/user/login', 'POST', [
            'email' => $user->email,
            'password' => 'correctpassword',
        ]);

        Validator::shouldReceive('make')
            ->once()
            ->andReturnSelf();
        Validator::shouldReceive('fails')
            ->once()
            ->andReturn(false);

        User::shouldReceive('where')
            ->once()
            ->with('email', $user->email)
            ->andReturnSelf();
        User::shouldReceive('first')
            ->once()
            ->andReturn($user);

        Auth::shouldReceive('attempt')
            ->once()
            ->with([
                'email' => $user->email,
                'password' => 'correctpassword',
            ])
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->andReturn($user);

        $accessToken = 'mocked_access_token';
        $user->shouldReceive('createToken')
            ->once()
            ->with($request->email)
            ->andReturn((object)['plainTextToken' => $accessToken]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(200, $response->status());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user', $responseData);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals($accessToken, $responseData['token']);
        $this->assertEquals('User Login Success', $responseData['message']);
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

        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('The email field is required.', $responseData['errors'][0]);
        $this->assertEquals('The password field is required.', $responseData['errors'][1]);
    }

    public function testCorrectEmailWrongPassword()
    {
        $user = User::factory()->create([
            'email' => 'thisang@gmail.com',
            'password' => Hash::make('correctpassword'),
        ]);

        $request = Request::create('/api/user/login', 'POST', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(401, $response->status());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('Incorrect password', $responseData['message']);
    }

    public function testWrongEmailCorrectPassword()
    {
        $request = Request::create('/api/user/login', 'POST', [
            'email' => 'wrong@example.com',
            'password' => 'correctpassword',
        ]);

        $controller = new UserController();
        $response = $controller->login($request);

        $this->assertEquals(404, $response->status());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('message', $responseData);
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

        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('failed', $responseData['status']);
        $this->assertEquals('Email does not exist', $responseData['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
