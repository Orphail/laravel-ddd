<?php

namespace Src\Agenda\Company\Application\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Src\Agenda\Company\Application\DTO\CompanyData;
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

    public function store(Company $company): CompanyWithMainAddressData
    {
        return DB::transaction(function () use ($company) {
            $companyEloquent = CompanyMapper::toEloquent($company);
            $companyEloquent->save();
            $main_address = $company->getMainAddress();
            $mainAddressEloquent = AddressMapper::toEloquent($main_address);
            $mainAddressEloquent->company_id = $companyEloquent->id;
            $mainAddressEloquent->save();
            return CompanyWithMainAddressData::fromEloquent($companyEloquent);
        });
    }
    public function update(CompanyData $company): void
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