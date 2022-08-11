<?php

namespace Src\User\Infrastructure\EloquentModels;

class PasswordCast implements \Illuminate\Contracts\Database\Eloquent\CastsAttributes
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