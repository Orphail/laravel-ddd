<?php

namespace Src\Agenda\User\Application\UseCases\Queries;

use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindUserByEmailQuery implements QueryInterface
{
    private UserRepositoryInterface $repository;
    private UserPolicy $policy;

    public function __construct(
        private readonly string $email
    )
    {
        $this->repository = app()->make(UserRepositoryInterface::class);
        $this->policy = new UserPolicy();
    }

    public function handle(): array
    {
        authorize('findByEmail', $this->policy);
        return $this->repository->findByEmail($this->email)->toArray();
    }
}