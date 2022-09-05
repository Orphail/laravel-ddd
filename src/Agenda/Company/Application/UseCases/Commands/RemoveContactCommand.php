<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\ContactRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class RemoveContactCommand implements CommandInterface
{
    private ContactRepositoryInterface $repository;
    private CompanyPolicy $policy;

    public function __construct(
        private readonly int $contact_id
    )
    {
        $this->repository = app()->make(ContactRepositoryInterface::class);
        $this->policy = new CompanyPolicy();
    }

    public function execute(): void
    {
        authorize('removeContact', $this->policy);
        $this->repository->remove($this->contact_id);
    }
}