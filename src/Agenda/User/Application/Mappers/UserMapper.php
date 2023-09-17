<?php

namespace Src\Agenda\User\Application\Mappers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Role;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;
use Src\Agenda\User\Domain\Model\ValueObjects\Name;
use Src\Agenda\User\Domain\Model\ValueObjects\Username;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;

class UserMapper
{
    public static function fromRequest(Request $request, ?int $user_id = null): User
    {
        return new User(
            id: $user_id,
            username: new Username($request->string('username')),
            name: new Name($request->string('name')),
            email: new Email($request->string('email')),
            role: new Role($request->string('role')),
            is_admin: $request->boolean('is_admin', false),
            is_active: $request->boolean('is_active', true),
        );
    }

    public static function fromEloquent(UserEloquentModel $userEloquent): User
    {
        // $avatar = new Avatar(binary_data: null, filename: $userEloquent->avatar);
        return new User(
            id: $userEloquent->id,
            username: new Username($userEloquent->username),
            name: new Name($userEloquent->name),
            email: new Email($userEloquent->email),
            role: new Role($userEloquent->role),
            is_admin: $userEloquent->is_admin,
            is_active: $userEloquent->is_active
        );
    }

    public static function fromAuth(Authenticatable $userEloquent): User
    {
        // $avatar = new Avatar(binary_data: null, filename: $userEloquent->avatar);
        return new User(
            id: $userEloquent->id,
            username: new Username($userEloquent->username),
            name: new Name($userEloquent->name),
            email: new Email($userEloquent->email),
            role: new Role($userEloquent->role),
            is_admin: $userEloquent->is_admin,
            is_active: $userEloquent->is_active
        );
    }

    public static function toEloquent(User $user): UserEloquentModel
    {
        $userEloquent = new UserEloquentModel();
        if ($user->id) {
            $userEloquent = UserEloquentModel::query()->findOrFail($user->id);
        }
        $userEloquent->username = $user->username;
        $userEloquent->name = $user->name;
        $userEloquent->email = $user->email;
        $userEloquent->role = $user->role;
        $userEloquent->is_admin = $user->is_admin;
        $userEloquent->is_active = $user->is_active;
        return $userEloquent;
    }
}
