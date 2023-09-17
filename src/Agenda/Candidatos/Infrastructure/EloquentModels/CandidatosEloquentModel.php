<?php

namespace Src\Agenda\Candidatos\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class CandidatosEloquentModel extends Model
{
    protected $table = 'candidato';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'source',
        'owner',
    ];

    public array $rules = [
        'name' => 'required|string|max:255',
        'source' => 'required|string|max:255',
        'owner' => 'required',
        'created_by' => 'required',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function owner()
    {
        return $this->hasMany(User::class, 'owner');
    }

    public function created_by()
    {
        return $this->hasMany(User::class, 'created_by');
    }

}
