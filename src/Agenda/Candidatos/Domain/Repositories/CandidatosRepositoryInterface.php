<?php

namespace Src\Agenda\Candidatos\Domain\Repositories;

use Src\Agenda\Candidatos\Domain\Model\Candidatos;

interface CandidatosRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): Candidatos;
    public function store(Candidatos $Candidatos): Candidatos;
}
