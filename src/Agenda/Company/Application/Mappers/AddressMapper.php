<?php

namespace Src\Agenda\Company\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;
use Src\Agenda\Company\Domain\Model\ValueObjects\City;
use Src\Agenda\Company\Domain\Model\ValueObjects\Country;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
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
            name: new Name($request->string('name')),
            type: $request->enum('type', AddressType::class),
            street: new Street($request->string('street')),
            zip_code: new ZipCode($request->string('zip_code')),
            city: new City($request->string('city')),
            country: new Country($request->string('country')),
            phone: new Phone($request->string('phone'), true),
            email: new Email($request->string('email'), true),
        );
    }

    public static function fromArray(array $address): Address
    {
        $addressEloquentModel = new AddressEloquentModel($address);
        $addressEloquentModel->id = $address['id'] ?? null;
        return self::fromEloquent($addressEloquentModel);
    }

    public static function fromEloquent(AddressEloquentModel $addressEloquent): Address
    {
        return new Address(
            id: $addressEloquent->id,
            name: new Name($addressEloquent->name),
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
            $addressEloquent = AddressEloquentModel::query()->findOrFail($address->id);
        }
        $addressEloquent->name = $address->name;
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