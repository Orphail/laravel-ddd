<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\DepartmentRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class RemoveDepartmentCommand implements CommandInterface
{
    private DepartmentRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly int $department_id
    )
    {
        $this->repository = app()->make(DepartmentRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function execute(): void
    {
        authorize('removeDepartment', $this->policy);
        $this->repository->remove($this->department_id);
    }
}