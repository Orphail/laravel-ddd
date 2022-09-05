<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Src\Agenda\User\Application\DTO\UserData;
use Src\Agenda\User\Domain\Factories\UserFactory;

trait WithLogin
{
    use WithFaker, WithCompanies;

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
            'id'        => $userEloquentModel->id,
            'company_id' => $userEloquentModel->company_id,
            'email'     => $user->email,
            'password'  => $password,
        ];
    }

    private function newLoggedAdmin(): array
    {
        $credentials = $this->validCredentials(['is_admin' => true]);
        $response = $this->post('auth/login', $credentials);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    private function newLoggedUser(): array
    {
        $company = $this->newCompany();
        $credentials = $this->validCredentials(['is_admin' => false, 'company_id' => $company->id]);
        $response = $this->post('auth/login', $credentials);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    private function getToken(TestResponse $response)
    {
        $arResponse = json_decode($response->getContent(), true);
        return $arResponse['accessToken'];
    }
}