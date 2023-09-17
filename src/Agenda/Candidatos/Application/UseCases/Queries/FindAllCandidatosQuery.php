<?php

namespace Src\Agenda\Candidatos\Application\UseCases\Queries;

use Src\Common\Domain\QueryInterface;
use Src\Agenda\Candidatos\Domain\Policies\CandidatosPolicy;
use Src\Agenda\Candidatos\Domain\Repositories\CandidatosRepositoryInterface;

class FindAllCandidatosQuery implements QueryInterface
{
    private CandidatosRepositoryInterface $repository;

    public function __construct(
    )
    {
        $this->repository = app()->make(CandidatosRepositoryInterface::class);
    }

    public function handle(): array
    {
        authorize('findAll', CandidatosPolicy::class);
        return $this->repository->findAll();
    }
}
