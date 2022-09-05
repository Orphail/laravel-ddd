<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithLogin;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithLogin;

    protected User $user;
    protected string $login_uri;
    protected string $logout_uri;
    protected string $refresh_uri;
    protected string $me_uri;

    /**
     * Create a new faker instance.
     *
     * @return void
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->login_uri = '/auth/login';
        $this->logout_uri = '/auth/logout';
        $this->refresh_uri = '/auth/refresh';
        $this->me_uri = '/auth/me';
        $this->token = $this->newLoggedAdmin()['token'];
    }

    /** @test */
    function active_user_can_login()
    {
        $credentials = $this->validCredentials(['is_active' => true]);

        $this->post($this->login_uri, $credentials)
            ->assertSessionHasNoErrors()
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['accessToken']);

        $this->assertAuthenticated();
    }

    /** @test */
    function inactive_user_cannot_login()
    {
        $credentials = $this->validCredentials(['is_active' => false]);

        $this->post($this->login_uri, $credentials)
            ->assertSessionHasNoErrors()
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'Unauthorized']);
    }

    /** @test */
    public function user_can_not_login_without_credentials()
    {
        $this->post($this->login_uri, [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee([
                'email'    => 'The email field is required.',
                'password' => 'The password field is required.',
            ]);
    }

    /** @test */
    public function user_can_not_login_without_email()
    {
        $credentials = $this->validCredentials();
        unset($credentials['email']);

        $this->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee([
                'email' => 'The email field is required.',
            ]);
    }

    /** @test */
    public function user_can_not_login_without_password()
    {
        $credentials = $this->validCredentials();
        unset($credentials['password']);

        $this->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee([
                'password' => 'The password field is required.',
            ]);
    }

    /** @test */
    public function user_can_not_login_with_invalid_credentials()
    {
        $credentials = ['email' => 'test@invalid.credentials', 'password' => 'invalid'];

        $this->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'Unauthorized']);
    }

    /** @test */
    public function user_can_get_his_own_info()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get($this->me_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['id', 'name', 'email', 'avatar', 'is_admin', 'is_active']);
    }

    /** @test */
    public function logged_user_can_logout()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->post($this->logout_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['message' => 'Successfully logged out']);

        $this->assertGuest();
    }

    /** @test */
    public function logged_user_can_refresh()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->post($this->refresh_uri)
            ->assertSessionHasNoErrors()
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['accessToken']);

        $newToken = $this->getToken($response);

        // The previous token should be invalid.
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->post($this->refresh_uri)
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertSee(['status']);

        // The new token should be valid.
        $this->withHeaders(['Authorization' => 'Bearer ' . $newToken])->get($this->me_uri)
            ->assertStatus(Response::HTTP_OK);
    }
}