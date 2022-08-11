<?php

namespace Src\User\Domain\Factories;

use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObject\Avatar;
use Src\User\Domain\Model\ValueObject\Email;
use Src\User\Domain\Model\ValueObject\Name;

class UserFactory
{
    public static function new(array $attributes = null): User
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'avatar' => null,
            'is_admin' => true,
            'is_active' => true,
        ];

        $attributes = array_replace($defaults, $attributes);

        return new User(
            id: null,
            name: new Name($attributes['name']),
            email: new Email($attributes['email']),
            avatar: new Avatar($attributes['avatar']),
            is_admin: $attributes['is_admin'],
            is_active: $attributes['is_active']
        );
    }
}