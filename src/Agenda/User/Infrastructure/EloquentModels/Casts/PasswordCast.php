<?php

namespace Src\Agenda\User\Infrastructure\EloquentModels\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PasswordCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return bcrypt($value);
    }
}