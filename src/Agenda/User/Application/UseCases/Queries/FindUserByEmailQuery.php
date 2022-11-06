<?php

namespace Src\Agenda\User\Application\UseCases\Queries;

use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindUserByEmailQuery implements QueryInterface
{
    private UserRepositoryInterface $repository;

    public function __construct(
        private readonly string $email
    )
    {
        $this->repository = app()->make(UserRepositoryInterface::class);
    }

    public function handle(): array
    {
        authorize('findByEmail', UserPolicy::class);
        return $this->repository->findByEmail($this->email);
    }
}