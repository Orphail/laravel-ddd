<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\User\Domain\Factories\UserFactory;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithLogin;

class UserTest extends TestCase
{
    use RefreshDatabase, WithLogin;

    private string $index_uri;
    private string $user_uri;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user_uri = '/user';
        $this->index_uri = $this->user_uri . '/index';
        $this->random_avatar_uri = $this->user_uri . '/random-avatar';
        $this->token = $this->loginAndGetToken();
    }

    /** @test */
    public function can_retrieve_all_users()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get($this->index_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($numberUsers + 1); // +1 because of the admin user
    }

    /** @test */
    public function can_get_specific_user_by_id()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->numberBetween(1, $numberUsers);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'name', 'email', 'avatar', 'is_admin', 'is_active']);
    }

    /** @test */
    public function can_create_a_user()
    {
        $password = $this->faker->password(8);
        $requestBody = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'avatar' => 'https://doodleipsum.com/300/avatar-2?shape=circle',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $expectedResponse = [
            'id' => 2,
            'name' => $requestBody['name'],
            'email' => $requestBody['email'],
            'is_admin' => false,
            'is_active' => true
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post($this->user_uri, $requestBody)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($expectedResponse);

        // Assert cannot create user with same email
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post($this->user_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'El email ya está en uso']);

    }

    /** @test */
    public function cannot_create_user_with_invalid_email()
    {
        $password = $this->faker->password(8);
        $requestBodyInvalidEmail = [
            'name' => $this->faker->name,
            'email' => 'invalid-email',
            'avatar' => 'https://doodleipsum.com/300/avatar-2?shape=circle',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post($this->user_uri, $requestBodyInvalidEmail)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'El campo debe ser un email válido']);
    }

    /** @test */
    public function cannot_create_user_with_invalid_password()
    {
        $password = $this->faker->password(8);
        $requestBody = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'avatar' => 'https://doodleipsum.com/300/avatar-2?shape=circle',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $requestBodyInvalidPassword = $requestBody;
        $requestBodyInvalidPassword['password'] = '1234';
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post($this->user_uri, $requestBodyInvalidPassword)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'La contraseña debe tener al menos 8 caracteres']);

        $requestBodyNoPasswordConfirmation = $requestBody;
        unset($requestBodyNoPasswordConfirmation['password_confirmation']);
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->post($this->user_uri, $requestBodyNoPasswordConfirmation)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Las contraseñas no coinciden']);
    }

    /** @test */
    public function can_update_a_user()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->numberBetween(1, $numberUsers);

        $password = $this->faker->password(8);
        $requestBody = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'avatar' => false,
            'is_active' => false,
            'update_avatar' => true,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $expectedResponse = [
            'id' => $randomUserId,
            'name' => $requestBody['name'],
            'email' => $requestBody['email'],
            'avatar' => false,
            'is_admin' => false,
            'is_active' => false
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->patch($this->user_uri . '/' . $randomUserId, $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedResponse);

    }

    /** @test */
    public function cannot_update_user_with_invalid_password()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->numberBetween(1, $numberUsers);

        $password = $this->faker->password(8);
        $requestBody = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'avatar' => 'https://doodleipsum.com/300/avatar-2?shape=circle',
            'is_active' => false,
            'update_avatar' => true,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $requestBodyInvalidPassword = $requestBody;
        $requestBodyInvalidPassword['password'] = '1234';
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->patch($this->user_uri . '/' . $randomUserId, $requestBodyInvalidPassword)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'La contraseña debe tener al menos 8 caracteres']);

        $requestBodyNoPasswordConfirmation = $requestBody;
        unset($requestBodyNoPasswordConfirmation['password_confirmation']);
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->patch($this->user_uri . '/' . $randomUserId, $requestBodyNoPasswordConfirmation)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Las contraseñas no coinciden']);

    }

    /** @test */
    public function can_delete_a_user()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->numberBetween(1, $numberUsers);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->delete($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function cannot_delete_user_if_does_not_exists()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->delete($this->user_uri . '/' . 3)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_random_image() // TODO - Mock HTTP request
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get($this->random_avatar_uri)
            ->assertStatus(Response::HTTP_OK);
    }

    private function createRandomUsers($usersNumber = 1): void
    {
        foreach (range(1, $usersNumber) as $_) {
            $user = UserFactory::new();
            UserEloquentModel::create(array_merge($user->toArray(), ['password' => $this->faker->password(8)]));
        }
    }
}
