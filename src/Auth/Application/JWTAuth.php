<?php

namespace Src\Auth\Application;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Src\User\Domain\Model\User;
use Src\User\Domain\Model\ValueObjects\Avatar;
use Src\User\Domain\Model\ValueObjects\Email;
use Src\User\Domain\Model\ValueObjects\Name;
use Src\User\Domain\Repositories\AvatarRepositoryInterface;
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
        if (!$token = auth()->attempt($credentials)) {
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
        $eloquentUser = auth()->user();
        return new User(
            id: $eloquentUser->id,
            name: new Name($eloquentUser->name),
            email: new Email($eloquentUser->email),
            avatar: $this->avatarRepository->retrieveAvatarFile(new Avatar($eloquentUser->avatar)),
            is_admin: $eloquentUser->is_admin,
            is_active: $eloquentUser->is_active
        );
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