<?php

namespace Src\Agenda\Candidatos\Application\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Src\Agenda\Candidatos\Application\Mappers\CandidatosMapper;
use Src\Agenda\Candidatos\Domain\Model\Candidatos;
use Src\Agenda\Candidatos\Domain\Repositories\CandidatosRepositoryInterface;
use Src\Agenda\Candidatos\Infrastructure\EloquentModels\CandidatosEloquentModel;
use Illuminate\Support\Facades\Cache;

class CandidatosRepository implements CandidatosRepositoryInterface
{
    public function findAll(): array
    {
        return Cache::remember('candidatos_' . auth()->user()->id, 60, function () {
            $candidatos = [];
            foreach (CandidatosEloquentModel::when(auth()->user()->role == 'agent' ?? false, function ($query) {
                return $query->where('owner', auth()->user()->id);
            })->get() as $CandidatosEloquent) {
                $candidatos[] = CandidatosMapper::fromEloquent($CandidatosEloquent);
            }
            return $candidatos;
        });
    }

    public function findById(int $id): Candidatos
    {
        return Cache::remember('candidato_' . $id . '_user_' . auth()->user()->id, 60, function () use ($id) {
            $CandidatosEloquent = CandidatosEloquentModel::when(auth()->user()->role == 'agent' ?? false, function ($query) {
                return $query->where('owner', auth()->user()->id);
            })->findOrFail($id);
            return CandidatosMapper::fromEloquent($CandidatosEloquent);
        });
    }

    public function store(Candidatos $Candidatos): Candidatos
    {
        return DB::transaction(function () use ($Candidatos) {
            $CandidatosEloquent = CandidatosMapper::toEloquent($Candidatos);
            $CandidatosEloquent->save();
            return CandidatosMapper::fromEloquent($CandidatosEloquent);
        });
    }
}
