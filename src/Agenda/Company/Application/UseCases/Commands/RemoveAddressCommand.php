<?php

namespace Src\Agenda\Company\Application\UseCases\Commands;

use Src\Agenda\Company\Domain\Policies\CompanyPolicy;
use Src\Agenda\Company\Domain\Repositories\AddressRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class RemoveAddressCommand implements CommandInterface
{
    private AddressRepositoryInterface $repository;

    public function __construct(
        private readonly int $address_id
    )
    {
        $this->repository = app()->make(AddressRepositoryInterface::class);
    }

    public function execute(): void
    {
        authorize('removeAddress', CompanyPolicy::class);
        $this->repository->remove($this->address_id);
    }
}