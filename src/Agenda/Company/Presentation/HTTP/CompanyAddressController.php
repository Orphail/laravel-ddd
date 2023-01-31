<?php

namespace Src\Agenda\Company\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Agenda\Company\Application\Mappers\AddressMapper;
use Src\Agenda\Company\Application\UseCases\Commands\PersistAddressesCommand;
use Src\Agenda\Company\Application\UseCases\Commands\RemoveAddressCommand;
use Src\Agenda\Company\Application\UseCases\Queries\FindCompanyByIdQuery;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response;

class CompanyAddressController
{
    public function add(int $company_id, Request $request): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();

            $address = AddressMapper::fromRequest($request);
            $company->addAddress($address);
            (new PersistAddressesCommand($company))->execute();
            return response()->success($address->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(int $company_id, int $address_id, Request $request): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();

            $address = AddressMapper::fromRequest($request, $address_id);
            $company->updateAddress($address);
            (new PersistAddressesCommand($company))->execute();
            return response()->success($address->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function remove(int $company_id, int $address_id): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();
            $company->removeAddress($address_id);
            (new RemoveAddressCommand($address_id))->execute();
            return response()->success(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

}