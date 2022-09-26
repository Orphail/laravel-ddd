<?php

namespace Src\Agenda\User\Domain\Policies;

use Src\Agenda\User\Domain\Model\User;

class UserPolicy
{
    public static function findAll(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function findById(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function findByEmail(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function store(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function update(User $user): bool
    {
        return auth()->user()?->is_admin || auth()->user()?->id == $user->id;
    }

    public static function delete(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function getRandomAvatar(): bool
    {
        return true;
    }

}