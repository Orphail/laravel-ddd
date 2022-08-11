<?php

namespace Src\User\Application\Providers;


use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Src\User\Domain\Repositories\UserRepositoryInterface::class,
            \Src\User\Application\Repositories\Eloquent\UserRepository::class
        );

        $this->app->bind(
            \Src\User\Domain\Repositories\AvatarRepositoryInterface::class,
            \Src\User\Application\Repositories\Local\AvatarRepository::class
        );
    }
}