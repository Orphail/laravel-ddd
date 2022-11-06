<?php

namespace Src\Agenda\Company\Domain\Factories;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\ValueObjects\Addresses;
use Src\Agenda\Company\Domain\Model\ValueObjects\Contacts;
use Src\Agenda\Company\Domain\Model\ValueObjects\Departments;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;

class CompanyFactory
{
    public static function new(array $attributes = null): Company
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'id' => null,
            'fiscal_name' => fake()->name,
            'social_name' => fake()->company,
            'vat' => fake()->bothify('?#########'),
            'is_active' => true,
            'main_address' => AddressFactory::new(),
        ];

        $attributes = array_replace($defaults, $attributes);

        return new Company(
            id: null,
            fiscal_name: new FiscalName($attributes['fiscal_name']),
            social_name: new SocialName($attributes['social_name']),
            vat: new Vat($attributes['vat']),
            addresses: new Addresses([$attributes['main_address']]),
            departments: new Departments([]),
            contacts: new Contacts([]),
            is_active: $attributes['is_active'],
        );
    }
}