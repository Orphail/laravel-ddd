<?php

namespace Src\Agenda\Company\Domain\Repositories;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Department;

interface DepartmentRepositoryInterface
{
    public function upsertAll(Company $company): void;
    public function remove(int $department_id): void;
}