<?php

namespace Src\Agenda\Company\Application\Providers;

use Illuminate\Support\ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Src\Agenda\Company\Domain\Repositories\CompanyRepositoryInterface::class,
            \Src\Agenda\Company\Application\Repositories\Eloquent\CompanyRepository::class
        );

        $this->app->bind(
            \Src\Agenda\Company\Domain\Repositories\AddressRepositoryInterface::class,
            \Src\Agenda\Company\Application\Repositories\Eloquent\AddressRepository::class
        );

        $this->app->bind(
            \Src\Agenda\Company\Domain\Repositories\ContactRepositoryInterface::class,
            \Src\Agenda\Company\Application\Repositories\Eloquent\ContactRepository::class
        );

        $this->app->bind(
            \Src\Agenda\Company\Domain\Repositories\DepartmentRepositoryInterface::class,
            \Src\Agenda\Company\Application\Repositories\Eloquent\DepartmentRepository::class
        );
    }
}