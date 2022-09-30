<?php

namespace Src\Agenda\Company\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddressEloquentModel extends Model
{
    protected $table = 'company_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'type',
        'street',
        'zip_code',
        'city',
        'country',
        'phone',
        'email',
    ];

    public array $rules = [
        'company_id' => 'required|integer',
        'name' => 'required|string',
        'type' => 'required|in:fiscal,logistic,administrative',
        'street' => 'required|string',
        'zip_code' => 'required|string',
        'city' => 'required|string',
        'country' => 'required|string',
        'phone' => 'nullable|string',
        'email' => 'nullable|email',
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
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyEloquentModel::class);
    }
}
