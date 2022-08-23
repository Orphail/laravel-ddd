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
    private function validCredentials(): array
    {
        $password = $this->faker->password(8);
        $user = UserFactory::new();
        $userEloquentModel = UserData::toEloquent($user);
        $userEloquentModel->password = $password;
        $userEloquentModel->save();

        return [
            'email'    => $user->email,
            'password' => $password,
        ];
    }

    private function loginAndGetToken(): string
    {
        $credentials = $this->validCredentials();
        $response = $this->post('auth/login', $credentials);
        return $this->getToken($response);
    }

    private function getToken(TestResponse $response)
    {
        $arResponse = json_decode($response->getContent(), true);
        return $arResponse['accessToken'];
    }
}