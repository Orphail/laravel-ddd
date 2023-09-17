<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\WithLogin;
use Src\Agenda\User\Domain\Model\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithLogin;

    /**
     * Create a new faker instance.
     *
     * @return void
     */

    protected User $user;
    protected string $auth_uri;
    protected string $logout_uri;
    protected string $refresh_uri;
    protected string $me_uri;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth_uri = '/auth';
        $this->logout_uri = '/auth/logout';
        $this->refresh_uri = '/auth/refresh';
        $this->me_uri = '/auth/me';
        $this->token = $this->newLoggedAdmin()['token'];
    }

    /** @test */
    function active_user_can_login()
    {
        $credentials = $this->validCredentials(['is_active' => true]);

        $this->post($this->auth_uri, $credentials)
            ->assertSessionHasNoErrors()
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);

        $this->assertAuthenticated();
    }

    /** @test */
    function inactive_user_cannot_login()
    {
        $credentials = $this->validCredentials(['is_active' => false]);

        $this->post($this->auth_uri, $credentials)
            ->assertSessionHasNoErrors()
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['errors' => 'Password incorrect for: ' . $credentials['username']]);
    }

    /** @test */
    public function user_can_not_login_without_credentials()
    {
        $this->post($this->auth_uri, [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee([
                'username'    => 'The username field is required.',
                'password' => 'The password field is required.',
            ]);
    }

    /** @test */
    public function user_can_not_login_without_username()
    {
        $credentials = $this->validCredentials();
        unset($credentials['username']);

        $this->post($this->auth_uri, $credentials)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee([
                'username' => 'The username field is required.',
            ]);
    }

    /** @test */
    public function user_can_not_login_without_password()
    {
        $credentials = $this->validCredentials();
        unset($credentials['password']);

        $this->post($this->auth_uri, $credentials)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee([
                'password' => 'The password field is required.',
            ]);
    }

    /** @test */
    public function user_can_not_login_with_invalid_credentials()
    {
        $credentials = ['username' => 'test@invalid.credentials', 'password' => 'invalid'];

        $this->post($this->auth_uri, $credentials)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['errors' => 'Password incorrect for: ' . $credentials['username']]);
    }

    /** @test */
    public function user_can_get_his_own_info()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->get($this->me_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['id', 'name', 'email', 'username', 'is_admin', 'is_active']);
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
            ->assertSee(['token']);

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
