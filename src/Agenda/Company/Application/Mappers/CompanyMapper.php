<?php

namespace Src\Agenda\Company\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Exceptions\RequiredMainAddressException;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\ValueObjects\Addresses;
use Src\Agenda\Company\Domain\Model\ValueObjects\Contacts;
use Src\Agenda\Company\Domain\Model\ValueObjects\Departments;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;

class CompanyMapper
{
    public static function fromRequest(Request $request, ?int $company_id = null): Company
    {
        $main_address = $request->input('main_address');
        if (!$main_address) {
            throw new RequiredMainAddressException();
        }
        $addresses = [$main_address, ...$request->input('addresses', [])];
        return new Company(
            id: $company_id,
            fiscal_name: new FiscalName($request->string('fiscal_name')),
            social_name: new SocialName($request->string('social_name')),
            vat: new Vat($request->string('vat')),
            addresses: new Addresses(array_map(function ($address) {
                return AddressMapper::fromArray($address);
            }, $addresses)),
            departments: new Departments(array_map(function ($department) {
                return DepartmentMapper::fromArray($department);
            }, $request->input('departments', []))),
            contacts: new Contacts(array_map(function ($contact) {
                return ContactMapper::fromArray($contact);
            }, $request->input('contacts', []))),
            is_active: $request->boolean('is_active', true),
        );
    }

    public static function fromEloquent(CompanyEloquentModel $companyEloquent, bool $with_addresses = false, bool $with_departments = false, bool $with_contacts = false): Company
    {
        $addresses = $with_addresses ? array_map(function ($address) {
            return AddressMapper::fromArray($address);
        }, $companyEloquent->addresses?->toArray() ?? []) : [];
        $departments = $with_departments ? array_map(function ($address) {
            return DepartmentMapper::fromArray($address);
        }, $companyEloquent->departments?->toArray() ?? []) : [];
        $contacts = $with_contacts ? array_map(function ($address) {
            return ContactMapper::fromArray($address);
        }, $companyEloquent->contacts?->toArray() ?? []) : [];
        return new Company(
            id: $companyEloquent->id,
            fiscal_name: new FiscalName($companyEloquent->fiscal_name),
            social_name: new SocialName($companyEloquent->social_name),
            vat: new Vat($companyEloquent->vat),
            addresses: new Addresses($addresses),
            departments: new Departments($departments),
            contacts: new Contacts($contacts),
            is_active: $companyEloquent->is_active,
        );
    }

    public static function toEloquent(Company $company): CompanyEloquentModel
    {
        $companyEloquent = new CompanyEloquentModel();
        if ($company->id) {
            $companyEloquent = CompanyEloquentModel::query()->findOrFail($company->id);
        }
        $companyEloquent->fiscal_name = $company->fiscal_name;
        $companyEloquent->social_name = $company->social_name;
        $companyEloquent->vat = $company->vat;
        $companyEloquent->is_active = $company->is_active;
        return $companyEloquent;
    }
}