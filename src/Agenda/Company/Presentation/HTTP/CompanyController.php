<?php

namespace Src\Agenda\Company\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Agenda\Company\Application\DTO\CompanyData;
use Src\Agenda\Company\Application\DTO\CompanyUpdateData;
use Src\Agenda\Company\Application\Mappers\CompanyMapper;
use Src\Agenda\Company\Application\UseCases\Commands\DestroyCompanyCommand;
use Src\Agenda\Company\Application\UseCases\Commands\StoreCompanyCommand;
use Src\Agenda\Company\Application\UseCases\Commands\UpdateCompanyCommand;
use Src\Agenda\Company\Application\UseCases\Queries\FindAllClientsQuery;
use Src\Agenda\Company\Application\UseCases\Queries\FindAllCompaniesQuery;
use Src\Agenda\Company\Application\UseCases\Queries\FindCompanyByIdQuery;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response;

class CompanyController
{

    public function index(): JsonResponse
    {
        try {
            return response()->success((new FindAllCompaniesQuery())->handle());
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->success((new FindCompanyByIdQuery($id))->handle());
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $newCompany = CompanyMapper::fromRequest($request);
            $company = (new StoreCompanyCommand($newCompany))->execute();
            return response()->success($company, Response::HTTP_CREATED);
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(int $company_id, Request $request): JsonResponse
    {
        try {
            $company = CompanyData::fromRequest($request, $company_id);
            (new UpdateCompanyCommand($company))->execute();
            return response()->success($company->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function destroy(int $company_id, Request $request): JsonResponse
    {
        try {
            (new DestroyCompanyCommand($company_id))->execute();
            return response()->success(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}