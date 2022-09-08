<?php

namespace Src\Agenda\Company\Application\Repositories\Eloquent;

use Src\Agenda\Company\Application\DTO\CompanyWithMainAddressData;
use Src\Agenda\Company\Application\Mappers\AddressMapper;
use Src\Agenda\Company\Application\Mappers\CompanyMapper;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function findAll(): array
    {
        $companies = [];
        foreach (CompanyEloquentModel::all() as $companyEloquent) {
            $companies[] = CompanyWithMainAddressData::fromEloquent($companyEloquent);
        }
        return $companies;
    }
    public function findById(string $id): Company
    {
        $companyEloquent = CompanyEloquentModel::query()->findOrFail($id);
        return CompanyMapper::fromEloquent($companyEloquent, true, true, true);
    }
    public function findByVat(string $vat): Company
    {
        $companyEloquent = CompanyEloquentModel::query()->where('vat', $vat)->firstOrFail();
        return CompanyMapper::fromEloquent($companyEloquent, true, true, true);
    }

    public function store(Company $company): Company
    {
        $companyEloquent = CompanyMapper::toEloquent($company);
        $companyEloquent->save();

        foreach ($company->addresses as $address) {
            $addressEloquent = AddressMapper::toEloquent($address);
            $addressEloquent->company_id = $companyEloquent->id;
            $addressEloquent->save();
        }

        return CompanyMapper::fromEloquent($companyEloquent, true, true);
    }
    public function update(Company $company): void
    {
        $companyArray = $company->toArray();
        $companyEloquent = CompanyEloquentModel::query()->findOrFail($company->id);
        $companyEloquent->fill($companyArray);
        $companyEloquent->save();
    }
    public function delete(int $company_id): void
    {
        $companyEloquent = CompanyEloquentModel::query()->findOrFail($company_id);
        $companyEloquent->delete();
    }
}