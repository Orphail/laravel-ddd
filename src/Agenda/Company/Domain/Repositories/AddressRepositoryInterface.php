<?php

namespace Src\Agenda\Company\Domain\Repositories;

use Src\Agenda\Company\Domain\Model\Company;

interface AddressRepositoryInterface
{
    public function upsertAll(Company $company): void;
    public function remove(int $address_id): void;
}