<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Src\Agenda\Candidatos\Application\Mappers\CandidatosMapper;
use Src\Agenda\Candidatos\Domain\Model\Candidatos;
use Src\Agenda\Candidatos\Domain\Factories\CandidatoFactory;

trait WithCandidatos
{
    use WithFaker;

    protected function newCandidato(?int $owner, ?int $created_by): Candidatos
    {
        $candidato = CandidatoFactory::new(['owner' => $owner , 'created_by' => $created_by]);
        $candidatoEloquent = CandidatosMapper::toEloquent($candidato);
        $candidatoEloquent->save();
        return CandidatosMapper::fromEloquent($candidatoEloquent);
    }

    protected function createRandomCandidato(int $candidatoCount, ?array $owners , ?int $created_by): array
    {
        $candidato_ids = [];
        foreach (range(1, $candidatoCount) as $_) {
            $candidato = $this->newCandidato((int) $this->faker->randomElement($owners), (int) $created_by);
            $candidato_ids[] = $candidato->id;
        }
        return $candidato_ids;
    }
}
