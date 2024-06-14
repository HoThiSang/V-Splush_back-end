<?php

namespace App\Models\Mocks;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class UserMock extends User
{
    /**
     * Mock the where method to return a new instance.
     */
    public static function where($column, $value)
    {
        return new self();
    }

    /**
     * Mock the first method to return null (no existing user).
     */
    public function first()
    {
        return null;
    }

    /**
     * Mock the create method to return a new instance with attributes.
     */
    public static function create(array $attributes = [])
    {
        $user = new self();
        $user->fill($attributes);
        $user->id = 1; // Mock an ID

        return $user;
    }

    /**
     * Mock the createToken method to simulate token creation.
     */
    public function createToken($name, $abilities = [])
    {
        // Generate a token similar to Laravel Sanctum's implementation
        $token = new PersonalAccessToken();
        $token->tokenable_id = $this->id;
        $token->name = $name;
        $token->token = hash('sha256', 'dummy_token');
        $token->abilities = $abilities;

        return $token;
    }
}
