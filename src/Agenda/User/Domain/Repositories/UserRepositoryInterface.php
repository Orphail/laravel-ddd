<?php

namespace Src\Agenda\User\Domain\Repositories;

//use Src\Agenda\UserEloquentModel\Infrastructure\Repositories\UserDoesNotExistException;

use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Password;

interface UserRepositoryInterface
{
    public function findAll(): array;

    public function findById(string $userId): User;

    public function findByEmail(string $email): User;

    public function store(User $user, Password $password): User;

    public function update(User $user, Password $password): void;

    public function delete(int $user_id): void;

}
