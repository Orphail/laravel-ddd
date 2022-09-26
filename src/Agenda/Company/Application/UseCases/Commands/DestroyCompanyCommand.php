<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class DestroyCompanyCommand implements CommandInterface
{
    private CompanyRepositoryInterface $repository;

    public function __construct(
        private readonly int $company_id
    )
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('delete', CompanyPolicy::class);
        $this->repository->delete($this->company_id);
    }
}