<?php

namespace Src\Agenda\User\Application\UseCases\Commands;

use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class DestroyUserCommand implements CommandInterface
{
    private UserRepositoryInterface $repository;
    private UserPolicy $policy;

    public function __construct(
        private readonly int $id
    )
    {
        $this->repository = app()->make(UserRepositoryInterface::class);
        $this->policy = new UserPolicy();
    }

    public function execute(): void
    {
        authorize('delete', $this->policy);
        $this->repository->delete($this->id);
    }
}