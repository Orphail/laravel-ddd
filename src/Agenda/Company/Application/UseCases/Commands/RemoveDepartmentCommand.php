<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\DepartmentRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class RemoveDepartmentCommand implements CommandInterface
{
    private DepartmentRepositoryInterface $repository;

    public function __construct(
        private readonly int $department_id
    )
    {
        $this->repository = app()->make(DepartmentRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('removeDepartment', CompanyPolicy::class);
        $this->repository->remove($this->department_id);
    }
}