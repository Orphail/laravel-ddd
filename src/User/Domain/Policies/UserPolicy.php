<?php

namespace Src\User\Domain\Policies;


use Src\User\Domain\Model\User;

class UserPolicy
{
    public function findAll(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function findById(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function findByEmail(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function store(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function update(User $user): bool
    {
        return auth()->user()?->is_admin || auth()->user()?->id == $user->id;
    }

    public function delete(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

}