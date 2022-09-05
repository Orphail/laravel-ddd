<?php

namespace Src\Agenda\Company\Application\UseCases\Queries;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindCompanyByVatQuery implements QueryInterface
{
    private CompanyRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly string $vat
    )
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function handle(): array
    {
        authorize('findByVat', $this->policy);
        return $this->repository->findByVat($this->vat)->toArray();
    }
}