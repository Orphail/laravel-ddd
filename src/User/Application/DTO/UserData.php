<?php

namespace Src\User\Application\DTO;

use Illuminate\Http\Request;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObject\Avatar;
use Src\User\Domain\Model\ValueObject\Email;
use Src\User\Domain\Model\ValueObject\Name;
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
            is_admin: $request->input('is_admin'),
            is_active: $request->input('is_active'),
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
}