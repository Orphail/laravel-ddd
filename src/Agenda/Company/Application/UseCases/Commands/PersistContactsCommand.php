<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\ContactRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class PersistContactsCommand implements CommandInterface
{
    private ContactRepositoryInterface $repository;

    public function __construct(
        private readonly Company $company
    )
    {
        $this->repository = app()->make(ContactRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('persistContacts', CompanyPolicy::class);
        $this->repository->upsertAll($this->company);
    }
}