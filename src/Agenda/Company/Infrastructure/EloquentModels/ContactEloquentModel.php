<?php

namespace Src\Agenda\Company\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class ContactEloquentModel extends Model
{
    protected $table = 'company_contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'contact_role',
        'email',
        'phone',
        'address_id',
    ];

    public array $rules = [
        'company_id' => 'required|integer',
        'address_id' => 'nullable|integer',
        'name' => 'required|string',
        'contact_role' => 'required|string',
        'email' => 'required|string',
        'phone' => 'required|string'
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
