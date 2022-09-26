<?php

namespace Src\Agenda\User\Domain\Factories;

use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Avatar;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;
use Src\Agenda\User\Domain\Model\ValueObjects\CompanyId;
use Src\Agenda\User\Domain\Model\ValueObjects\Name;

class UserFactory
{
    public static function new(array $attributes = null): User
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'company_id' => null,
            'avatar' => null,
            'is_admin' => true,
            'is_active' => true,
        ];

        $attributes = array_replace($defaults, $attributes);

        return (new User(
            id: null,
            name: new Name($attributes['name']),
            email: new Email($attributes['email']),
            company_id: new CompanyId($attributes['company_id']),
            avatar: new Avatar(binary_data: $attributes['avatar'], filename: null),
            is_admin: $attributes['is_admin'],
            is_active: $attributes['is_active']
        ))->validateNonAdminWithCompany();
    }
}