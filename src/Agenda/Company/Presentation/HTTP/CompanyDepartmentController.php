<?php

namespace Src\Agenda\Company\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Agenda\Company\Application\Mappers\DepartmentMapper;
use Src\Agenda\Company\Application\UseCases\Commands\PersistDepartmentsCommand;
use Src\Agenda\Company\Application\UseCases\Commands\RemoveDepartmentCommand;
use Src\Agenda\Company\Application\UseCases\Queries\FindCompanyByIdQuery;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response;

class CompanyDepartmentController
{
    public function add(int $company_id, Request $request): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();

            $department = DepartmentMapper::fromRequest($request);
            $company->addDepartment($department);
            (new PersistDepartmentsCommand($company))->execute();
            return response()->success($department->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(int $company_id, int $department_id, Request $request): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();

            $department = DepartmentMapper::fromRequest($request, $department_id);
            $company->updateDepartment($department);
            (new PersistDepartmentsCommand($company))->execute();
            return response()->success($department->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function remove(int $company_id, int $department_id): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();
            $company->removeDepartment($department_id);
            (new RemoveDepartmentCommand($department_id))->execute();
            return response()->success(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

}