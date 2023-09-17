<?php

namespace Src\Agenda\Candidatos\Application\UseCases\Commands;

use Src\Common\Domain\CommandInterface;
use Src\Agenda\Candidatos\Domain\Model\Candidatos;
use Src\Agenda\Candidatos\Domain\Policies\CandidatosPolicy;
use Src\Agenda\Candidatos\Domain\Repositories\CandidatosRepositoryInterface;

class StoreCandidatosCommand implements CommandInterface
{
    private CandidatosRepositoryInterface $repository;

    public function __construct(
        private readonly Candidatos $candidatos
    )
    {
        $this->repository = app()->make(CandidatosRepositoryInterface::class);
    }

    public function execute(): mixed
    {
        authorize('store', CandidatosPolicy::class);
        return $this->repository->store($this->candidatos);
    }
}
