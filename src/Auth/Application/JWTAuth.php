<?php

namespace Src\Auth\Application;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Src\Agenda\User\Application\Mappers\UserMapper;
use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;
use Src\Auth\Domain\AuthInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as TymonJWTAuth;

class JWTAuth implements AuthInterface
{
    private AvatarRepositoryInterface $avatarRepository;

    public function __construct(AvatarRepositoryInterface $avatarRepository)
    {
        $this->avatarRepository = $avatarRepository;
    }

    public function login(array $credentials): string
    {
        $user = UserEloquentModel::query()->where('email', $credentials['email'])->first();
        if (!$user || !$user->is_active) {
            throw new AuthenticationException();
        } elseif (!$token = auth()->attempt($credentials)) {
            throw new AuthenticationException();
        }
        return $token;
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function me(): User
    {
        return UserMapper::fromAuth(auth()->user());
    }

    public function refresh(): string
    {
        try {
            return TymonJWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            throw new AuthenticationException($e->getMessage());
        }
    }
}