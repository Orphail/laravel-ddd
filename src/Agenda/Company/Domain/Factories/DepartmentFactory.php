<?php

namespace Src\Agenda\Company\Domain\Factories;

use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;

class DepartmentFactory
{
    public static function new(array $attributes = null): Department
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'id' => null,
            'name' => fake()->name,
            'address_id' => 1,
            'is_active' => true,
        ];

        $attributes = array_replace($defaults, $attributes);

        return new Department(
            id: $attributes['id'],
            name: new Name($attributes['name']),
            address_id: $attributes['address_id'],
            is_active: $attributes['is_active']
        );
    }
}