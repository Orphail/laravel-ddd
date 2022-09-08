<?php

namespace Src\Agenda\Company\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;

class CompanyMapper
{
    public static function fromRequest(Request $request, ?int $company_id = null): Company
    {
        return new Company(
            id: $company_id,
            fiscal_name: new FiscalName($request->input('fiscal_name')),
            social_name: new SocialName($request->input('social_name')),
            vat: new Vat($request->input('vat')),
            addresses: array_map(function ($address) {
                return AddressMapper::fromArray($address);
            }, $request->input('addresses', [])),
            departments: array_map(function ($department) {
                return DepartmentMapper::fromArray($department);
            }, $request->input('departments', [])),
            contacts: array_map(function ($contact) {
                return ContactMapper::fromArray($contact);
            }, $request->input('contacts', [])),
            is_active: $request->input('is_active'),
        );
    }

    public static function fromEloquent(CompanyEloquentModel $companyEloquent, bool $with_addresses = false, bool $with_departments = false, bool $with_contacts = false): Company
    {
        return new Company(
            id: $companyEloquent->id,
            fiscal_name: new FiscalName($companyEloquent->fiscal_name),
            social_name: new SocialName($companyEloquent->social_name),
            vat: new Vat($companyEloquent->vat),
            addresses: $with_addresses ? $companyEloquent->addresses?->each(function ($address) {
                return AddressMapper::fromEloquent($address);
            })->toArray() ?? [] : [],
            departments: $with_departments ? $companyEloquent->departments?->each(function ($department) {
                return DepartmentMapper::fromEloquent($department);
            })->toArray() ?? [] : [],
            contacts: $with_contacts ? $companyEloquent->contacts?->each(function ($contact) {
                return ContactMapper::fromEloquent($contact);
            })->toArray() ?? [] : [],
            is_active: $companyEloquent->is_active,
        );
    }

    public static function toEloquent(Company $company): CompanyEloquentModel
    {
        $companyEloquent = new CompanyEloquentModel();
        if ($company->id) {
            $companyEloquent = CompanyEloquentModel::query()->find($company->id);
        }
        $companyEloquent->fiscal_name = $company->fiscal_name;
        $companyEloquent->social_name = $company->social_name;
        $companyEloquent->vat = $company->vat;
        $companyEloquent->is_active = $company->is_active;
        return $companyEloquent;
    }
}