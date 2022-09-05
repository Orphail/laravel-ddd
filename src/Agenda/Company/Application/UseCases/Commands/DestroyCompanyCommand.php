<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class DestroyCompanyCommand implements CommandInterface
{
    private CompanyRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly int $company_id
    )
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function execute(): void
    {
        authorize('delete', $this->policy);
        $this->repository->delete($this->company_id);
    }
}