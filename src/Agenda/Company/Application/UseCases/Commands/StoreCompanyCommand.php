<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Application\Exceptions\VatAlreadyUsedException;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;
use Src\Common\Domain\CommandInterface;

class StoreCompanyCommand implements CommandInterface
{
    private CompanyRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly Company $company
    )
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function execute(): Company
    {
        authorize('store', $this->policy);
        if (CompanyEloquentModel::query()->where('vat', $this->company->vat)->exists()) {
            throw new VatAlreadyUsedException();
        }

        return $this->repository->store($this->company);
    }
}