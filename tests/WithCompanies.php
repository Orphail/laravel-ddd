<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Src\Agenda\Company\Application\Mappers\AddressMapper;
use Src\Agenda\Company\Application\Mappers\CompanyMapper;
use Src\Agenda\Company\Application\Mappers\ContactMapper;
use Src\Agenda\Company\Application\Mappers\DepartmentMapper;
use Src\Agenda\Company\Domain\Factories\AddressFactory;
use Src\Agenda\Company\Domain\Factories\CompanyFactory;
use Src\Agenda\Company\Domain\Factories\ContactFactory;
use Src\Agenda\Company\Domain\Factories\DepartmentFactory;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;

trait WithCompanies
{
    use WithFaker;

    protected function newCompany($is_provider = false): Company
    {
        $company = CompanyFactory::new(['is_provider' => $is_provider]);
        $companyEloquent = CompanyMapper::toEloquent($company);
        $companyEloquent->save();
        foreach ($company->addresses as $address) {
            $addressEloquent = AddressMapper::toEloquent($address);
            $addressEloquent->company_id = $companyEloquent->id;
            $addressEloquent->save();
        }
        return CompanyMapper::fromEloquent($companyEloquent, true);
    }

    protected function createRandomCompanies(int $companiesCount): array
    {
        $company_ids = [];
        foreach (range(1, $companiesCount) as $_) {
            $company = $this->newCompany();
            $company_ids[] = $company->id;
        }
        return $company_ids;
    }

    protected function createAddress(int $company_id): Address
    {
        $address = AddressFactory::new(['type' => $this->faker->randomElement([AddressType::Administrative->value, AddressType::Logistic->value])]);
        $addressEloquent = AddressMapper::toEloquent($address);
        $addressEloquent->company_id = $company_id;
        $addressEloquent->save();
        return AddressMapper::fromEloquent($addressEloquent);
    }

    protected function createDepartment(int $company_id, int $address_id): Department
    {
        $department = DepartmentFactory::new();
        $departmentEloquent = DepartmentMapper::toEloquent($department);
        $departmentEloquent->company_id = $company_id;
        $departmentEloquent->address_id = $address_id;
        $departmentEloquent->save();
        return DepartmentMapper::fromEloquent($departmentEloquent);
    }

    protected function createContact(int $company_id): Contact
    {
        $contact = ContactFactory::new();
        $contactEloquent = ContactMapper::toEloquent($contact);
        $contactEloquent->company_id = $company_id;
        $contactEloquent->save();
        return ContactMapper::fromEloquent($contactEloquent);
    }

}