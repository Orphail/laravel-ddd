<?php

namespace Src\Agenda\Company\Domain\Factories;

use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Model\ValueObjects\ContactRole;
use Src\Agenda\Company\Domain\Model\ValueObjects\Email;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;

class ContactFactory
{
    public static function new(array $attributes = null): Contact
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'id' => null,
            'contact_role' => ContactRole::Administrative,
            'name' => fake()->name,
            'email' => fake()->email,
            'phone' => fake()->phoneNumber,
            'address_id' => null,
        ];

        $attributes = array_replace($defaults, $attributes);

        return new Contact(
            id: $attributes['id'],
            contact_role: $attributes['contact_role'],
            name: new Name($attributes['name']),
            email: new Email($attributes['email']),
            phone: new Phone($attributes['phone']),
            address_id: $attributes['address_id']
        );
    }
}