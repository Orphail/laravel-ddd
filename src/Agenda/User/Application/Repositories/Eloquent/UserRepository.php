<?php

namespace Src\Agenda\User\Application\Repositories\Eloquent;

use Src\Agenda\User\Application\DTO\UserData;
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
            $users[] = UserData::fromEloquent($userEloquent);
        }
        return $users;
    }

    public function findById(string $userId): User
    {
        $userEloquent = UserEloquentModel::query()->findOrFail($userId);
        return UserData::fromEloquent($userEloquent);
    }

    public function findByEmail(string $email): User
    {
        $userEloquent = UserEloquentModel::query()->where('email', $email)->firstOrFail();
        return UserData::fromEloquent($userEloquent);
    }

    public function store(User $user, Password $password): User
    {
        $userEloquent = new UserEloquentModel();
        $userEloquent->fill(array_merge($user->toArray(), ['password' => $password->value()]));
        $userEloquent->save();

        return UserData::fromEloquent($userEloquent);
    }

    public function update(User $user, Password $password): void
    {
        $userArray = $user->toArray();
        if ($password->isNotEmpty()) {
            $userArray['password'] = $password->value();
        }
        $userEloquent = UserEloquentModel::query()->findOrFail($user->id);
        $userEloquent->fill($userArray);
        $userEloquent->save();
    }

    public function delete(int $user_id): void
    {
        $userEloquent = UserEloquentModel::query()->findOrFail($user_id);
        $userEloquent->delete();
    }
}