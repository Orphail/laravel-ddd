<?php

namespace Src\User\Application\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Src\User\Application\DTO\UserData;
use Src\User\Application\Exceptions\EmailAlreadyUsedException;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Password;
use Src\User\Domain\Policies\UserPolicy;
use Src\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;

class UserRepository implements UserRepositoryInterface
{
    private AvatarRepositoryInterface $avatarRepository;
    private UserPolicy $userPolicy;

    public function __construct(AvatarRepositoryInterface $avatarRepository)
    {
        $this->avatarRepository = $avatarRepository;
        $this->userPolicy = new UserPolicy();
    }

    public function findAll(): Collection
    {
        authorize('findAll', $this->userPolicy);
        $users = collect();
        foreach (UserEloquentModel::all() as $userEloquent) {
            $users->push(UserData::fromEloquent($userEloquent, $this->avatarRepository));
        }
        return $users;
    }

    public function findById(string $userId): User
    {
        authorize('findById', $this->userPolicy);
        $userEloquent = UserEloquentModel::findOrFail($userId);
        return UserData::fromEloquent($userEloquent, $this->avatarRepository);
    }

    public function findByEmail(string $email): User
    {
        authorize('findByEmail', $this->userPolicy);
        $userEloquent = UserEloquentModel::where('email', $email)->firstOrFail();
        return UserData::fromEloquent($userEloquent, $this->avatarRepository);
    }

    public function store(User $user, Password $password): User
    {
        authorize('store', $this->userPolicy);
        if (UserEloquentModel::where('email', $user->email)->exists()) {
            throw new EmailAlreadyUsedException();
        }

        $avatar = $user->avatar;
        if ($avatar->isBinaryFile()) {
            $filename = $this->avatarRepository->storeAvatarFile($avatar, $user->name);
            $user->setAvatar($filename);
        }

        $userEloquent = new UserEloquentModel();
        $userEloquent->fill(array_merge($user->toArray(), ['password' => $password->value()]));
        $userEloquent->save();

        return UserData::fromEloquent($userEloquent, $this->avatarRepository);
    }

    public function update(User $user, Password $password, bool $updateAvatar): User
    {
        authorize('update', $this->userPolicy, ['user' => $user]);
        $avatar = $user->avatar;
        if ($avatar->isBinaryFile()) {
            $filename = $this->avatarRepository->storeAvatarFile($avatar, $user->name);
            $user->setAvatar($filename);
        }

        $userArray = $user->toArray();
        if ($password->isNotEmpty()) {
            $userArray['password'] = $password->value();
        }
        $userEloquent = UserEloquentModel::findOrFail($user->id);
        $userEloquent->fill($userArray);
        $userEloquent->save();

        return UserData::fromEloquent($userEloquent, $this->avatarRepository);
    }

    public function delete(int $user_id): void
    {
        authorize('delete', $this->userPolicy);
        $userEloquent = UserEloquentModel::findOrFail($user_id);
        $userEloquent->delete();
    }
}