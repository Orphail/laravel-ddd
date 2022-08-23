<?php

namespace Src\User\Application\DTO;

use Illuminate\Http\Request;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Avatar;
use Src\User\Domain\Model\ValueObjects\Email;
use Src\User\Domain\Model\ValueObjects\Name;
use Src\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;

class UserData
{
    public static function fromRequest(Request $request, ?int $user_id = null): User
    {
        return new User(
            id: $user_id,
            name: new Name($request->input('name')),
            email: new Email($request->input('email')),
            avatar: new Avatar($request->input('avatar')),
            is_admin: $request->input('is_admin') ?? false,
            is_active: $request->input('is_active') ?? true,
        );
    }

    public static function fromEloquent(UserEloquentModel $userEloquent, AvatarRepositoryInterface $avatarRepository): User
    {
        return new User(
            id: $userEloquent->id,
            name: new Name($userEloquent->name),
            email: new Email($userEloquent->email),
            avatar: $avatarRepository->retrieveAvatarFile(new Avatar($userEloquent->avatar)),
            is_admin: $userEloquent->is_admin,
            is_active: $userEloquent->is_active
        );
    }

    public static function toEloquent(User $user): UserEloquentModel
    {
        $userEloquent = new UserEloquentModel();
        if ($user->id) {
            $userEloquent = UserEloquentModel::find($user->id);
        }
        $userEloquent->name = $user->name;
        $userEloquent->email = $user->email;
        $userEloquent->avatar = $user->avatar;
        $userEloquent->is_admin = $user->is_admin;
        $userEloquent->is_active = $user->is_active;
        return $userEloquent;
    }
}