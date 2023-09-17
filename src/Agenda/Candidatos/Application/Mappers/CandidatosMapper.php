<?php

namespace Src\Agenda\Candidatos\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Candidatos\Domain\Model\Candidatos;
use Src\Agenda\Candidatos\Infrastructure\EloquentModels\CandidatosEloquentModel;
use Src\Agenda\Candidatos\Domain\Model\ValueObjects\CandidatoName;
use Src\Agenda\Candidatos\Domain\Model\ValueObjects\Source;

class CandidatosMapper
{
    public static function fromRequest(Request $request, ?int $candidato_id = null): Candidatos
    {
        return new Candidatos(
            id: $candidato_id,
            name: new CandidatoName($request->string('name')),
            source: new Source($request->string('source')),
            owner: $request->integer('owner'),
            created_at: null,
            created_by: auth()->user()->id,
        );
    }

    public static function fromEloquent(CandidatosEloquentModel $candidatoEloquent): Candidatos
    {
        return new Candidatos(
            id: $candidatoEloquent->id,
            name: new CandidatoName($candidatoEloquent->name),
            source: new Source($candidatoEloquent->source),
            owner: $candidatoEloquent->owner,
            created_at: $candidatoEloquent->created_at,
            created_by: $candidatoEloquent->created_by,
        );
    }

    public static function toEloquent(Candidatos $candidatos): CandidatosEloquentModel
    {
        $candidatoEloquent = new CandidatosEloquentModel();
        if ($candidatos->id) {
            $candidatoEloquent = CandidatosEloquentModel::query()->findOrFail($candidatos->id);
        }
        $candidatoEloquent->name = $candidatos->name;
        $candidatoEloquent->source = $candidatos->source;
        $candidatoEloquent->owner = $candidatos->owner;
        $candidatoEloquent->created_at = $candidatos->created_at;
        $candidatoEloquent->created_by = $candidatos->created_by;
        return $candidatoEloquent;
    }
}
