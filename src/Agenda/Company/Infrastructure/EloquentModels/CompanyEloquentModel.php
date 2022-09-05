<?php

namespace Src\Agenda\Company\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class CompanyEloquentModel extends Model
{
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fiscal_name',
        'social_name',
        'vat',
        'is_active',
    ];

    public array $rules = [
        'fiscal_name' => 'required|string|max:255',
        'social_name' => 'required|string|max:255',
        'vat' => 'required|string|max:255',
        'is_active' => 'required|boolean',
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
        'is_active' => 'boolean',
    ];

    protected $with = ['addresses'];

    public function addresses()
    {
        return $this->hasMany(AddressEloquentModel::class, 'company_id');
    }

    public function departments()
    {
        return $this->hasMany(DepartmentEloquentModel::class, 'company_id');
    }

    public function contacts()
    {
        return $this->hasMany(ContactEloquentModel::class, 'company_id');
    }

    public function getMainAddressAttribute()
    {
        return $this->addresses()->where('type', 'fiscal')->first();
    }
}
