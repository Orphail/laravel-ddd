<?php

namespace Src\Agenda\User\Application\UseCases\Queries;

use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindUserByIdQuery implements QueryInterface
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

    public function handle(): User
    {
        authorize('findById', $this->policy);
        return $this->repository->findById($this->id);
    }
}