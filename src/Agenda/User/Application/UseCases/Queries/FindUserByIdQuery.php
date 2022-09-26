<?php

namespace Src\Agenda\User\Application\UseCases\Queries;

use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindUserByIdQuery implements QueryInterface
{
    private UserRepositoryInterface $repository;

    public function __construct(
        private readonly int $id
    )
    {
        $this->repository = app()->make(UserRepositoryInterface::class);
    }

    public function handle(): User
    {
        authorize('findById', UserPolicy::class);
        return $this->repository->findById($this->id);
    }
}