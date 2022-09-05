<?php

namespace Src\Agenda\Company\Domain\Policies;

class CompanyPolicy
{
    public function findAll(): bool
    {
        return auth()->user()->is_admin;
    }

    public function findById(string $company_id): bool
    {
        return auth()->user()->is_admin || auth()->user()->company_id == $company_id;
    }

    public function findByVat(): bool
    {
        return auth()->user()->is_admin;
    }

    public function store(): bool
    {
        return auth()->user()->is_admin;
    }

    public function update(): bool
    {
        return auth()->user()->is_admin;
    }

    public function delete(): bool
    {
        return auth()->user()->is_admin;
    }

    public function persistAddresses(): bool
    {
        return auth()->user()->is_admin;
    }

    public function removeAddress(): bool
    {
        return auth()->user()->is_admin;
    }

    public function persistDepartments(): bool
    {
        return auth()->user()->is_admin;
    }

    public function removeDepartment(): bool
    {
        return auth()->user()->is_admin;
    }

    public function persistContacts(): bool
    {
        return auth()->user()->is_admin;
    }

    public function removeContact(): bool
    {
        return auth()->user()->is_admin;
    }
}