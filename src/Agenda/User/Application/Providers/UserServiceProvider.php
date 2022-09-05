<?php

namespace Src\Agenda\User\Application\Providers;


use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Src\Agenda\User\Domain\Repositories\UserRepositoryInterface::class,
            \Src\Agenda\User\Application\Repositories\Eloquent\UserRepository::class
        );

        $this->app->bind(
            \Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface::class,
            \Src\Agenda\User\Application\Repositories\Local\AvatarRepository::class
        );
    }
}