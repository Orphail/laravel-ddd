<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Src\User\Application\DTO\UserData;
use Src\User\Domain\Factories\UserFactory;

trait WithLogin
{
    use WithFaker;

    /**
     * Create a new user instance.
     */
    private function validCredentials(array $attributes = null): array
    {
        $password = $this->faker->password(8);
        $user = UserFactory::new($attributes);
        $userEloquentModel = UserData::toEloquent($user);
        $userEloquentModel->password = $password;
        $userEloquentModel->save();

        return [
            'email'    => $user->email,
            'password' => $password,
        ];
    }

    private function adminLoginAndGetToken(): string
    {
        $credentials = $this->validCredentials(['is_admin' => true]);
        $response = $this->post('auth/login', $credentials);
        return $this->getToken($response);
    }

    private function userLoginAndGetToken(): string
    {
        $credentials = $this->validCredentials(['is_admin' => false]);
        $response = $this->post('auth/login', $credentials);
        return $this->getToken($response);
    }

    private function getToken(TestResponse $response)
    {
        $arResponse = json_decode($response->getContent(), true);
        return $arResponse['accessToken'];
    }
}