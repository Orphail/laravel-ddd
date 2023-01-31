<?php

namespace Src\Agenda\Company\Domain\Repositories;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Address;

interface AddressRepositoryInterface
{
    public function upsertAll(Company $company): void;
    public function remove(int $address_id): void;
}