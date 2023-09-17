<?php

namespace Src\Agenda\Candidatos\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Src\Agenda\Candidatos\Application\Mappers\CandidatosMapper;
use Src\Agenda\Candidatos\Application\UseCases\Commands\StoreCandidatosCommand;
use Src\Agenda\Candidatos\Application\UseCases\Queries\FindAllCandidatosQuery;
use Src\Agenda\Candidatos\Application\UseCases\Queries\FindCandidatosByIdQuery;
use Symfony\Component\HttpFoundation\Response;

class CandidatosController
{
    public function index(): JsonResponse
    {
        try {
            $candidatos = (new FindAllCandidatosQuery())->handle();
            $expectedResponse = [
                "meta" => [
                    "success" =>
                    true, "errors" => []
                ],
                "data" => $candidatos
            ];
            return response()->json($expectedResponse);
        } catch (UnauthorizedUserException $e) {
            $unauthorizedResponse = [
                "meta" => [
                    "success" =>
                    false, "errors" => ["Token expired"]
                ],
            ];
            return response()->json($unauthorizedResponse, Response::HTTP_UNAUTHORIZED);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {

            $candidatos = (new FindCandidatosByIdQuery($id))->handle();
            $expectedResponse = [
                "meta" => [
                    "success" =>
                    true, "errors" => []
                ],
                "data" => $candidatos
            ];
            return response()->json($expectedResponse);
        } catch (UnauthorizedUserException $e) {
            $unauthorizedResponse = [
                "meta" => [
                    "success" =>
                    false, "errors" => ["Token expired"]
                ],
            ];
            return response()->json($unauthorizedResponse, Response::HTTP_UNAUTHORIZED);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $candidatos = CandidatosMapper::fromRequest($request);
            $candidatosData = (new StoreCandidatosCommand($candidatos))->execute();
            $expectedResponse = [
                "meta" => [
                    "success" =>
                    true, "errors" => []
                ],
                "data" => [
                    "id" => $candidatosData->id,
                    "name" => $candidatosData->name,
                    "source" => $candidatosData->source,
                    "owner" => $candidatosData->owner,
                    "created_by" =>  $candidatosData->created_by,
                ]
            ];
            return response()->json($expectedResponse, Response::HTTP_CREATED);
        } catch (\DomainException $domainException) {
            return response()->json(["error" => $domainException->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            $unauthorizedResponse = [
                "meta" => [
                    "success" =>
                    false, "errors" => ["Token expired"]
                ],
            ];
            return response()->json($unauthorizedResponse, Response::HTTP_UNAUTHORIZED);
        }
    }
}
