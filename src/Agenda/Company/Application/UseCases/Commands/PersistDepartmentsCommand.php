<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\DepartmentRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class PersistDepartmentsCommand implements CommandInterface
{
    private DepartmentRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly Company $company
    )
    {
        $this->repository = app()->make(DepartmentRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function execute(): void
    {
        authorize('persistDepartments', $this->policy);
        $this->repository->upsertAll($this->company);
    }
}