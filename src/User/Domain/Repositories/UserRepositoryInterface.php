<?php

namespace Src\User\Domain\Repositories;

//use Src\UserEloquentModel\Infrastructure\Repositories\UserDoesNotExistException;

use Illuminate\Support\Collection;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Password;

interface UserRepositoryInterface
{
    public function findAll(): Collection;

    public function findById(string $userId): User;

    public function findByEmail(string $email): User;

    public function store(User $user, Password $password): User;

    public function update(User $user, Password $password, bool $updateAvatar): User;

    public function delete(int $user_id): void;

}
