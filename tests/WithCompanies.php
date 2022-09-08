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

trait WithCompanies
{
    use WithFaker;

    public function newCompany(): Company
    {
        $company = CompanyFactory::new();
        $companyEloquentModel = CompanyMapper::toEloquent($company);
        $companyEloquentModel->save();
        foreach ($company->addresses as $address) {
            $addressEloquentModel = AddressMapper::toEloquent($address);
            $addressEloquentModel->company_id = $companyEloquentModel->id;
            $addressEloquentModel->save();
        }
        return CompanyMapper::fromEloquent($companyEloquentModel, true);
    }

    public function createRandomCompanies(int $companiesCount)
    {
        foreach (range(1, $companiesCount) as $_) {
            $this->newCompany();
        }
    }

    public function createAddress(int $company_id): Address
    {
        $address = AddressFactory::new();
        $addressEloquentModel = AddressMapper::toEloquent($address);
        $addressEloquentModel->company_id = $company_id;
        $addressEloquentModel->save();
        $address->id($addressEloquentModel->id);
        return $address;
    }

    public function createDepartment(int $company_id): Department
    {
        $department = DepartmentFactory::new();
        $departmentEloquentModel = DepartmentMapper::toEloquent($department);
        $departmentEloquentModel->company_id = $company_id;
        $departmentEloquentModel->save();
        $department->id($departmentEloquentModel->id);
        return $department;
    }

    public function createContact(int $company_id): Contact
    {
        $contact = ContactFactory::new();
        $contactEloquentModel = ContactMapper::toEloquent($contact);
        $contactEloquentModel->company_id = $company_id;
        $contactEloquentModel->save();
        $contact->id($contactEloquentModel->id);
        return $contact;
    }

}