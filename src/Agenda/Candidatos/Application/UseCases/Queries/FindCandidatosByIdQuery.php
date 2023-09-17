<?php

namespace Src\Agenda\Candidatos\Application\UseCases\Queries;

use Src\Common\Domain\QueryInterface;
use Src\Agenda\Candidatos\Domain\Model\Candidatos;
use Src\Agenda\Candidatos\Domain\Policies\CandidatosPolicy;
use Src\Agenda\Candidatos\Domain\Repositories\CandidatosRepositoryInterface;

class FindCandidatosByIdQuery implements QueryInterface
{
    private CandidatosRepositoryInterface $repository;

    public function __construct(
        private readonly int $id,
    )
    {
        $this->repository = app()->make(CandidatosRepositoryInterface::class);
    }

    public function handle(): Candidatos
    {
        authorize('findById', CandidatosPolicy::class);
        return $this->repository->findById($this->id);
    }
}
