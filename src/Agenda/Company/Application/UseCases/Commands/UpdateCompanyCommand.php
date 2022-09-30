<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Application\DTO\CompanyData;
use Src\Agenda\Company\Application\Exceptions\VatAlreadyUsedException;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;
use Src\Common\Domain\CommandInterface;

class UpdateCompanyCommand implements CommandInterface
{
    private CompanyRepositoryInterface $repository;

    public function __construct(
        private readonly CompanyData $company
    )
    {
        $this->repository = app()->make(CompanyRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('update', CompanyPolicy::class);
        if (CompanyEloquentModel::query()->where('vat', $this->company->vat)->where('id', '!=', $this->company->id)->exists()) {
            throw new VatAlreadyUsedException();
        }

        $this->repository->update($this->company);
    }
}