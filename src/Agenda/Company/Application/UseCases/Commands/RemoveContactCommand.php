<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\ContactRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class RemoveContactCommand implements CommandInterface
{
    private ContactRepositoryInterface $repository;

    public function __construct(
        private readonly int $contact_id
    )
    {
        $this->repository = app()->make(ContactRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('removeContact', CompanyPolicy::class);
        $this->repository->remove($this->contact_id);
    }
}