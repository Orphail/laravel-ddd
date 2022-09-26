<?php

namespace Src\Agenda\Company\Application\UseCases\Queries;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindCompanyByIdQuery implements QueryInterface
{
    private CompanyRepositoryInterface $repository;

    public function __construct(
        private readonly int $id
    )
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
    }

    public function handle(): Company
    {
        authorize('findById', CompanyPolicy::class, ['company_id' => $this->id]);
        return $this->repository->findById($this->id);
    }
}