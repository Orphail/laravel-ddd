<?php

namespace Src\Agenda\User\Application\DTO;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Avatar;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;
use Src\Agenda\User\Domain\Model\ValueObjects\CompanyId;
use Src\Agenda\User\Domain\Model\ValueObjects\Name;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;

class UserData
{
    public static function fromRequest(Request $request, ?int $user_id = null): User
    {
        return new User(
            id: $user_id,
            name: new Name($request->input('name')),
            email: new Email($request->input('email')),
            company_id: new CompanyId($request->input('company_id')),
            avatar: new Avatar($request->input('avatar')),
            is_admin: $request->input('is_admin') ?? false,
            is_active: $request->input('is_active') ?? true,
        );
    }

    public static function fromEloquent(UserEloquentModel $userEloquent): User
    {
        $avatarRepository = app()->make(AvatarRepositoryInterface::class);
        return new User(
            id: $userEloquent->id,
            name: new Name($userEloquent->name),
            email: new Email($userEloquent->email),
            company_id: new CompanyId($userEloquent->company_id),
            avatar: $avatarRepository->retrieveAvatarFile(new Avatar($userEloquent->avatar)),
            is_admin: $userEloquent->is_admin,
            is_active: $userEloquent->is_active
        );
    }

    public static function fromAuth(Authenticatable $userEloquent): User
    {
        $avatarRepository = app()->make(AvatarRepositoryInterface::class);
        return new User(
            id: $userEloquent->id,
            name: new Name($userEloquent->name),
            email: new Email($userEloquent->email),
            company_id: new CompanyId($userEloquent->company_id),
            avatar: $avatarRepository->retrieveAvatarFile(new Avatar($userEloquent->avatar)),
            is_admin: $userEloquent->is_admin,
            is_active: $userEloquent->is_active
        );
    }

    public static function toEloquent(User $user): UserEloquentModel
    {
        $userEloquent = new UserEloquentModel();
        if ($user->id) {
            $userEloquent = UserEloquentModel::query()->find($user->id);
        }
        $userEloquent->name = $user->name;
        $userEloquent->email = $user->email;
        $userEloquent->company_id = $user->company_id->value();
        $userEloquent->avatar = $user->avatar;
        $userEloquent->is_admin = $user->is_admin;
        $userEloquent->is_active = $user->is_active;
        return $userEloquent;
    }
}