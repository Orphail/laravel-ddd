<?php

namespace Src\Agenda\User\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Src\Agenda\User\Application\UseCases\Commands\GetRandomAvatarCommand;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response;

class GetRandomAvatarController
{
    public function __invoke(): JsonResponse
    {
        try {
            return response()->json((new GetRandomAvatarCommand())->execute());
        } catch (UnauthorizedUserException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }
}