<?php

namespace Src\Agenda\Company\Presentation\HTTP;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Agenda\Company\Application\Mappers\ContactMapper;
use Src\Agenda\Company\Application\UseCases\Commands\PersistContactsCommand;
use Src\Agenda\Company\Application\UseCases\Commands\RemoveContactCommand;
use Src\Agenda\Company\Application\UseCases\Queries\FindCompanyByIdQuery;
use Src\Common\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response;

class CompanyContactController
{
    public function add(int $company_id, Request $request): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();

            $contact = ContactMapper::fromRequest($request);
            $company->addContact($contact);
            (new PersistContactsCommand($company))->execute();
            return response()->success($contact->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(int $company_id, int $contact_id, Request $request): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();

            $contact = ContactMapper::fromRequest($request, $contact_id);
            $company->updateContact($contact);
            (new PersistContactsCommand($company))->execute();
            return response()->success($contact->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function remove(int $company_id, int $contact_id): JsonResponse
    {
        try {
            $company = (new FindCompanyByIdQuery($company_id))->handle();
            $company->removeContact($contact_id);
            (new RemoveContactCommand($contact_id))->execute();
            return response()->success(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedUserException $e) {
            return response()->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}