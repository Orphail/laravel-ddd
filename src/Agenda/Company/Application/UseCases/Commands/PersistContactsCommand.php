<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\ContactRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class PersistContactsCommand implements CommandInterface
{
    private ContactRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly Company $company
    )
    {
        $this->repository = app()->make(ContactRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function execute(): void
    {
        authorize('persistContacts', $this->policy);
        $this->repository->upsertAll($this->company);
    }
}