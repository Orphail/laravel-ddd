<?php

namespace Src\Agenda\Candidatos\Application\Providers;

use Illuminate\Support\ServiceProvider;

class CandidatosServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Src\Agenda\Candidatos\Domain\Repositories\CandidatosRepositoryInterface::class,
            \Src\Agenda\Candidatos\Application\Repositories\Eloquent\CandidatosRepository::class
        );
    }
}
