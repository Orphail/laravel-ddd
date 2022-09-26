<?php

namespace Src\Agenda\Company\Application\UseCases\Queries;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindAllCompaniesQuery implements QueryInterface
{
    private CompanyRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
    }

    public function handle(): array
    {
        authorize('findAll', CompanyPolicy::class);
        return $this->repository->findAll();
    }
}