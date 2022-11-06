<?php

namespace Src\Agenda\User\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Src\Agenda\User\Application\UseCases\Queries\GetRandomAvatarQuery;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response;

class GetRandomAvatarController
{
    public function __invoke(): JsonResponse
    {
        try {
            return response()->success((new GetRandomAvatarQuery())->handle());
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}