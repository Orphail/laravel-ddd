<?php

namespace Src\Agenda\Company\Domain\Policies;

class CompanyPolicy
{
    public static function findAll(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function findById(string $company_id): bool
    {
        return auth()->user()->is_admin || auth()->user()->company_id == $company_id;
    }

    public static function findByVat(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function store(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function update(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function delete(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function persistAddresses(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function removeAddress(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function persistDepartments(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function removeDepartment(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function persistContacts(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function removeContact(): bool
    {
        return auth()->user()->is_admin;
    }
}