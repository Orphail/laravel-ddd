<?php

namespace Src\Agenda\User\Domain\Factories;

use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;
use Src\Agenda\User\Domain\Model\ValueObjects\Username;
use Src\Agenda\User\Domain\Model\ValueObjects\Name;
use Src\Agenda\User\Domain\Model\ValueObjects\Role;

class UserFactory
{
    public static function new(array $attributes = null): User
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'username' => fake()->userName(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'role' => 'manager',
            'is_admin' => true,
            'is_active' => true,
            'last_login' => now()->format('d-m-Y H:i:s'),
        ];

        $attributes = array_replace($defaults, $attributes);

        return (new User(
            id: null,
            username: new Username($attributes['username']),
            name: new Name($attributes['name']),
            email: new Email($attributes['email']),
            role: new Role($attributes['role']),
            is_admin: $attributes['is_admin'],
            is_active: $attributes['is_active']
        ));
    }
}
