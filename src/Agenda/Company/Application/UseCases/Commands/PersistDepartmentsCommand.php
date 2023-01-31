<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\DepartmentRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class PersistDepartmentsCommand implements CommandInterface
{
    private DepartmentRepositoryInterface $repository;

    public function __construct(
        private readonly Company $company
    )
    {
        $this->repository = app()->make(DepartmentRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('persistDepartments', CompanyPolicy::class);
        $this->repository->upsertAll($this->company);
    }
}