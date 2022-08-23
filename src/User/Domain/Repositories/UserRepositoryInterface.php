<?php

namespace Src\User\Domain\Repositories;

//use Src\UserEloquentModel\Infrastructure\Repositories\UserDoesNotExistException;

use Illuminate\Support\Collection;
use Src\Common\Exceptions\UnauthorizedUserException;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Password;

interface UserRepositoryInterface
{
    /* @throws UnauthorizedUserException */
    public function findAll(): Collection;

    /* @throws UnauthorizedUserException */
    public function findById(string $userId): User;

    /* @throws UnauthorizedUserException */
    public function findByEmail(string $email): User;

    /* @throws UnauthorizedUserException */
    public function store(User $user, Password $password): User;

    /* @throws UnauthorizedUserException */
    public function update(User $user, Password $password, bool $updateAvatar): User;

    /* @throws UnauthorizedUserException */
    public function delete(int $user_id): void;

}
