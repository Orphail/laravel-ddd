<?php

namespace Src\Agenda\User\Application\Repositories\Eloquent;

use Src\Agenda\User\Application\Mappers\UserMapper;
use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Password;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;

class UserRepository implements UserRepositoryInterface
{
    public function findAll(): array
    {
        $users = [];
        foreach (UserEloquentModel::all() as $userEloquent) {
            $users[] = UserMapper::fromEloquent($userEloquent);
        }
        return $users;
    }

    public function findById(string $userId): User
    {
        $userEloquent = UserEloquentModel::query()->findOrFail($userId);
        return UserMapper::fromEloquent($userEloquent);
    }

    public function findByEmail(string $email): User
    {
        $userEloquent = UserEloquentModel::query()->where('email', $email)->firstOrFail();
        return UserMapper::fromEloquent($userEloquent);
    }

    public function store(User $user, Password $password): User
    {
        $userEloquent = UserMapper::toEloquent($user);
        $userEloquent->password = $password->value;
        $userEloquent->save();

        return UserMapper::fromEloquent($userEloquent);
    }

    public function update(User $user, Password $password): void
    {
        $userEloquent = UserMapper::toEloquent($user);
        if ($password->isNotEmpty()) {
            $userEloquent->password = $password->value;
        }
        $userEloquent->save();
    }

    public function delete(int $user_id): void
    {
        $userEloquent = UserEloquentModel::query()->findOrFail($user_id);
        $userEloquent->delete();
    }
}