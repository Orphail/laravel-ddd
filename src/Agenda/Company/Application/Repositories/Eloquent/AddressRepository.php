<?php

namespace Src\Agenda\Company\Application\Repositories\Eloquent;

use Src\Agenda\Company\Application\DTO\AddressData;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Repositories\AddressRepositoryInterface;
use Src\Agenda\Company\Infrastructure\EloquentModels\AddressEloquentModel;

class AddressRepository implements AddressRepositoryInterface
{
    public function upsertAll(Company $company): void
    {
        foreach ($company->addresses as $address) {
            $addressEloquent = AddressData::toEloquent($address);
            $addressEloquent->company_id = $company->id;
            $addressEloquent->save();
        }
    }
    public function remove(int $address_id): void
    {
        $addressEloquent = AddressEloquentModel::query()->findOrFail($address_id);
        $addressEloquent->delete();
    }
}