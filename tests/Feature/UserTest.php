<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class UserTest extends TestCase
{
    use WithUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user_uri = '/user';
        $this->index_uri = $this->user_uri . '/index';
    }

    /** @test */
    public function admin_can_retrieve_all_users()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->index_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($numberUsers + 2); // +2 because of the admin and user + 2 because of seeded users
    }

    /** @test */
    public function admin_can_get_specific_user_by_id()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'name', 'email', 'is_admin', 'is_active']);
    }

    /** @test */
    public function admin_can_create_a_user()
    {

        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role' => 'manager',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $expectedResponse = [
            'username' => $requestBody['username'],
            'name' => $requestBody['name'],
            'email' => $requestBody['email'],
            'role' => $requestBody['role'],
            'is_admin' => false,
            'is_active' => true
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->user_uri, $requestBody)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($expectedResponse);

        // Assert cannot create user with same email
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->user_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'El email ya está en uso']);
    }

    /** @test */
    public function cannot_create_user_with_invalid_email()
    {

        $password = $this->faker->password(8);
        $requestBodyInvalidEmail = [
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => 'invalid-email',
            'role' => 'manager',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->user_uri, $requestBodyInvalidEmail)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Must be a valid email']);
    }

    /** @test */
    public function cannot_create_user_with_invalid_password()
    {

        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role' => 'manager',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $requestBodyInvalidPassword = $requestBody;
        $requestBodyInvalidPassword['password'] = '1234';
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->user_uri, $requestBodyInvalidPassword)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'The password needs to be at least 8 characters long']);

        $requestBodyNoPasswordConfirmation = $requestBody;
        unset($requestBodyNoPasswordConfirmation['password_confirmation']);
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->user_uri, $requestBodyNoPasswordConfirmation)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Passwords do not match']);
    }

    /** @test */
    public function admin_can_update_any_user()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role' => 'manager',
            'is_active' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $expectedResponse = [
            'id' => $randomUserId,
            'username' => $requestBody['username'],
            'name' => $requestBody['name'],
            'email' => $requestBody['email'],
            'role' => $requestBody['role'],
            'is_admin' => false,
            'is_active' => false
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->user_uri . '/' . $randomUserId, $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedResponse);
    }

    /** @test */
    public function cannot_update_user_with_invalid_password()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role' => 'manager',
            'is_active' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $requestBodyInvalidPassword = $requestBody;
        $requestBodyInvalidPassword['password'] = '1234';
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->user_uri . '/' . $randomUserId, $requestBodyInvalidPassword)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'The password needs to be at least 8 characters long']);

        $requestBodyNoPasswordConfirmation = $requestBody;
        unset($requestBodyNoPasswordConfirmation['password_confirmation']);
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->user_uri . '/' . $randomUserId, $requestBodyNoPasswordConfirmation)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Passwords do not match']);
    }

    /** @test */
    public function admin_can_delete_a_user()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function cannot_delete_user_if_does_not_exists()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->user_uri . '/' . 999)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // User Tests
    /** @test */
    public function user_cannot_retrieve_all_users()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $this->createRandomUsers($numberUsers);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->get($this->index_uri)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }

    /** @test */
    public function user_cannot_get_specific_user_by_id()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->get($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }

    /** @test */
    public function user_cannot_create_user()
    {

        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->username,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role' => 'manager',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->post($this->user_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }

    /** @test */
    public function user_cannot_update_any_user_except_itself()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        // Update another user
        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->username,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role' => 'manager',
            'is_active' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->put($this->user_uri . '/' . $randomUserId, $requestBody)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);

        // Update itself
        $password = $this->faker->password(8);
        $requestBody = [
            'username' => $this->faker->username,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'role' => 'manager',
            'is_active' => true,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $expectedResponse = [
            'id' => $this->userData['id'],
            'name' => $requestBody['name'],
            'email' => $requestBody['email'],
            'role' => $requestBody['role'],
            'is_active' => $requestBody['is_active']
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->put($this->user_uri . '/' . $this->userData['id'], $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedResponse);
    }

    /** @test */
    public function user_cannot_delete_a_user()
    {
        $numberUsers = $this->faker->numberBetween(1, 10);
        $user_ids = $this->createRandomUsers($numberUsers);
        $randomUserId = $this->faker->randomElement($user_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->delete($this->user_uri . '/' . $randomUserId)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }
}
