<?php

namespace Src\Agenda\Company\Domain\Factories;

use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;
use Src\Agenda\Company\Domain\Model\ValueObjects\City;
use Src\Agenda\Company\Domain\Model\ValueObjects\Country;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Agenda\Company\Domain\Model\ValueObjects\Street;
use Src\Agenda\Company\Domain\Model\ValueObjects\ZipCode;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;

class AddressFactory
{
    public static function new(array $attributes = null): Address
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'id' => null,
            'name' => fake()->company,
            'type' => AddressType::Fiscal->value,
            'street' => fake()->streetName(),
            'zip_code' => fake()->postcode(),
            'city' => fake()->city(),
            'country' => fake()->countryCode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
        ];

        $attributes = array_replace($defaults, $attributes);

        return new Address(
            id: $attributes['id'],
            name: new Name($attributes['name']),
            type: AddressType::from($attributes['type']),
            street: new Street($attributes['street']),
            zip_code: new ZipCode($attributes['zip_code']),
            city: new City($attributes['city']),
            country: new Country($attributes['country']),
            phone: new Phone($attributes['phone'], true),
            email: new Email($attributes['email'], true),
        );
    }

}