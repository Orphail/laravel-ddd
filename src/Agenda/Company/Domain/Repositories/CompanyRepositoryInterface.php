<?php

namespace Src\Agenda\Company\Domain\Repositories;

use Src\Agenda\Company\Domain\Model\Company;

interface CompanyRepositoryInterface
{
    public function findAll(): array;
    public function findById(string $id): Company;
    public function findByVat(string $vat): Company;

    public function store(Company $company): Company;
    public function update(Company $company): void;
    public function delete(int $company_id): void;
}