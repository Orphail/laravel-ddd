<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Src\Agenda\User\Application\Mappers\UserMapper;
use Src\Agenda\User\Domain\Factories\UserFactory;

trait WithLogin
{
    use WithFaker, WithCandidatos;

    /**
     * Create a new user instance.
     */
    protected function validCredentials(array $attributes = null): array
    {
        $password = $this->faker->password(8);
        $user = UserFactory::new($attributes);
        $userEloquent = UserMapper::toEloquent($user);

        $userEloquent->password = $password;
        $userEloquent->save();

        return [
            'id' => $userEloquent->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'password' => $password,
        ];
    }

    protected function newLoggedAdmin(): array
    {
        $credentials = $this->validCredentials(['is_admin' => true]);
        $response = $this->post('auth', $credentials);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    protected function newLoggedUser(): array
    {
        $credentials = $this->validCredentials(['is_admin' => false]);
        $response = $this->post('auth', $credentials);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    protected function newLoggedAgent(): array
    {
        $credentials = $this->validCredentials(['role' => "agent"]);
        $response = $this->post('auth', $credentials);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    protected function newLoggedManager(): array
    {
        $credentials = $this->validCredentials(['role' => "manager"]);
        $response = $this->post('auth', $credentials);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    protected function getToken(TestResponse $response)
    {
        $arResponse = json_decode($response->getContent(), true);
        return $arResponse["data"]["token"];
    }
}
