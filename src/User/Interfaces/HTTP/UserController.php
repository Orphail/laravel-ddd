<?php

namespace Src\User\Interfaces\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\User\Application\DTO\UserData;
use Src\User\Domain\Model\ValueObjects\Password;
use Src\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Exceptions\UnauthorizedUserException;
use Src\Common\Infrastructure\Laravel\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    private UserRepositoryInterface $repository;
    private AvatarRepositoryInterface $avatarRepository;

    public function __construct(UserRepositoryInterface $userRepository, AvatarRepositoryInterface $avatarRepository)
    {
        $this->repository = $userRepository;
        $this->avatarRepository = $avatarRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json($this->repository->findAll());
        } catch (UnauthorizedUserException $unauthorizedUserException) {
            return response()->json(['error' => $unauthorizedUserException->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $userData = UserData::fromRequest($request);
            $password = new Password($request->input('password'), $request->input('password_confirmation'));
            $user = $this->repository->store($userData, $password);
            return response()->json($user->toArray(), Response::HTTP_CREATED);
        } catch (\DomainException $domainException) {
            return response()->json(['error' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $unauthorizedUserException) {
            return response()->json(['error' => $unauthorizedUserException->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $user_id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $user_id, Request $request): JsonResponse
    {
        try {
            $userData = UserData::fromRequest($request, $user_id);
            $password = new Password($request->input('password'), $request->input('password_confirmation'));
            $user = $this->repository->update($userData, $password, (bool)$request->get('update_avatar'));
            return response()->json($user->toArray(), Response::HTTP_OK);
        } catch (\DomainException $domainException) {
            return response()->json(['error' => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $unauthorizedUserException) {
            return response()->json(['error' => $unauthorizedUserException->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $user_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $user_id, Request $request): JsonResponse
    {
        try {
            $this->repository->delete($user_id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedUserException $unauthorizedUserException) {
            return response()->json(['error' => $unauthorizedUserException->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->repository->findById($id)->toArray());
        } catch (UnauthorizedUserException $unauthorizedUserException) {
            return response()->json(['error' => $unauthorizedUserException->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getRandomAvatar(): JsonResponse
    {
        return response()->json($this->avatarRepository->getRandomAvatar()->getPath());
    }
}
