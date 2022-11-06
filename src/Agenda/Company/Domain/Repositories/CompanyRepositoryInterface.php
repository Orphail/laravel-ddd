<?php

namespace Src\Agenda\Company\Domain\Repositories;

use Src\Agenda\Company\Application\DTO\CompanyData;
use Src\Agenda\Company\Application\DTO\CompanyWithMainAddressData;
use Src\Agenda\Company\Domain\Model\Company;

interface CompanyRepositoryInterface
{
    public function findAll(): array;
    public function findById(string $id): Company;
    public function findByVat(string $vat): Company;

    public function store(Company $company): CompanyWithMainAddressData;
    public function update(CompanyData $company): void;
    public function delete(int $company_id): void;
}