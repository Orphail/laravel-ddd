<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;

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

        $userArray = $user->toArray();
        $userArray['password'] = $password;
        UserEloquentModel::create($userArray);

        return [
            'email'    => $user->email(),
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