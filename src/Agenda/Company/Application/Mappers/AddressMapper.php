<?php

namespace Src\Agenda\Company\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;
use Src\Agenda\Company\Domain\Model\ValueObjects\City;
use Src\Agenda\Company\Domain\Model\ValueObjects\Country;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Agenda\Company\Domain\Model\ValueObjects\Street;
use Src\Agenda\Company\Domain\Model\ValueObjects\ZipCode;
use Src\Agenda\Company\Infrastructure\EloquentModels\AddressEloquentModel;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;

class AddressMapper
{
    public static function fromRequest(Request $request, ?int $address_id = null): Address
    {
        return new Address(
            id: $address_id,
            type: AddressType::from($request->input('type')),
            street: new Street($request->input('street')),
            zip_code: new ZipCode($request->input('zip_code')),
            city: new City($request->input('city')),
            country: new Country($request->input('country')),
            phone: new Phone($request->input('phone'), true),
            email: new Email($request->input('email'), true),
        );
    }

    public static function fromArray(array $address): Address
    {
        return new Address(
            id: $address['id'] ?? null,
            type: AddressType::from($address['type']),
            street: new Street($address['street']),
            zip_code: new ZipCode($address['zip_code']),
            city: new City($address['city']),
            country: new Country($address['country']),
            phone: new Phone($address['phone'], true),
            email: new Email($address['email'], true),
        );
    }

    public static function fromEloquent(AddressEloquentModel $addressEloquent): Address
    {
        return new Address(
            id: $addressEloquent->id,
            type: AddressType::from($addressEloquent->type),
            street: new Street($addressEloquent->street),
            zip_code: new ZipCode($addressEloquent->zip_code),
            city: new City($addressEloquent->city),
            country: new Country($addressEloquent->country),
            phone: new Phone($addressEloquent->phone, true),
            email: new Email($addressEloquent->email, true),
        );
    }

    public static function toEloquent(Address $address): AddressEloquentModel
    {
        $addressEloquent = new AddressEloquentModel();
        if ($address->id) {
            $addressEloquent = AddressEloquentModel::query()->find($address->id);
        }
        $addressEloquent->type = $address->type->value;
        $addressEloquent->street = $address->street;
        $addressEloquent->zip_code = $address->zip_code;
        $addressEloquent->city = $address->city;
        $addressEloquent->country = $address->country;
        $addressEloquent->phone = $address->phone;
        $addressEloquent->email = $address->email;
        return $addressEloquent;
    }
}