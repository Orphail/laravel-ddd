<?php

namespace Src\Agenda\Company\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class DepartmentEloquentModel extends Model
{
    protected $table = 'company_departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'address_id',
        'name',
        'is_active'
    ];

    public array $rules = [
        'company_id' => 'required|integer',
        'address_id' => 'nullable|integer',
        'name' => 'required|string',
        'is_active' => 'required|boolean'
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'address_id' => 'integer',
        'is_active' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyEloquentModel::class);
    }

    public function address()
    {
        return $this->belongsTo(AddressEloquentModel::class);
    }
}
