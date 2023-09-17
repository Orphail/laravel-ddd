<?php

namespace Src\Agenda\Candidatos\Domain\Policies;

class CandidatosPolicy
{
    public static function findAll(): bool
    {
        return auth()->user()->role == 'manager' || auth()->user()->role == 'agent' ?? false;
    }

    public static function findById(): bool
    {
        return auth()->user()->role == 'manager' || auth()->user()->role == 'agent' ?? false;
    }

    public static function store(): bool
    {
        return auth()->user()->role == 'manager' ?? false;
    }

}
