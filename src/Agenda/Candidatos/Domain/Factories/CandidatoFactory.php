<?php

namespace Src\Agenda\Candidatos\Domain\Factories;

use Src\Agenda\Candidatos\Domain\Model\Candidatos;
use Src\Agenda\Candidatos\Domain\Model\ValueObjects\CandidatoName;
use Src\Agenda\Candidatos\Domain\Model\ValueObjects\Source;

class CandidatoFactory
{
    public static function new(array $attributes = null): Candidatos
    {
        $attributes = $attributes ?: [];

        $defaults = [
            'name' => fake()->name(),
            'source' => fake()->country(),
            'owner' => auth()->user()->id,
            'created_by' => auth()->user()->id,
        ];

        $attributes = array_replace($defaults, $attributes);

        return (new Candidatos(
            id: null,
            name: new CandidatoName($attributes['name']),
            source: new Source($attributes['source']),
            owner: $attributes['owner'],
            created_by: $attributes['created_by'],
        ));
    }
}
