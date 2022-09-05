<?php

namespace Src\Agenda\User\Infrastructure\EloquentModels;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Src\Agenda\User\Infrastructure\EloquentModels\Casts\PasswordCast;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserEloquentModel extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'company_id',
        'avatar',
        'password',
        'is_admin',
        'is_active'
    ];

    public array $rules = [
        'name' => 'required',
        'email' => 'required',
        'company_id' => 'nullable',
        'avatar' => 'nullable',
        'password' => 'confirmed|min:8|nullable',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'avatar' => 'string',
        'password' => PasswordCast::class
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
