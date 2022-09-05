<?php

namespace Src\Agenda\User\Interfaces\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Agenda\User\Application\DTO\UserData;
use Src\Agenda\User\Application\UseCases\Commands\DestroyUserCommand;
use Src\Agenda\User\Application\UseCases\Commands\StoreUserCommand;
use Src\Agenda\User\Application\UseCases\Commands\UpdateUserCommand;
use Src\Agenda\User\Application\UseCases\Queries\FindAllUsersQuery;
use Src\Agenda\User\Application\UseCases\Queries\FindUserByIdQuery;
use Src\Agenda\User\Domain\Model\ValueObjects\Password;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Src\Common\Infrastructure\Laravel\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            return response()->json((new FindAllUsersQuery())->handle());
        } catch (UnauthorizedUserException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json((new FindUserByIdQuery($id))->handle());
        } catch (UnauthorizedUserException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $userData = UserData::fromRequest($request);
            $userData->validateNonAdminWithCompany();
            $password = new Password($request->input('password'), $request->input('password_confirmation'));
            $user = (new StoreUserCommand($userData, $password))->execute();
            return response()->json($user->toArray(), Response::HTTP_CREATED);
        } catch (\DomainException $domainException) {
            return response()->json(['error' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(int $user_id, Request $request): JsonResponse
    {
        try {
            $user = UserData::fromRequest($request, $user_id);
            $password = new Password($request->input('password'), $request->input('password_confirmation'));
            (new UpdateUserCommand($user, $password))->execute();
            return response()->json($user->toArray(), Response::HTTP_OK);
        } catch (\DomainException $domainException) {
            return response()->json(['error' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function destroy(int $user_id): JsonResponse
    {
        try {
            (new DestroyUserCommand($user_id))->execute();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedUserException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }
}
